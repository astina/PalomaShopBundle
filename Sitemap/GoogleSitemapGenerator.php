<?php

namespace Paloma\ShopBundle\Sitemap;

use Paloma\Shop\Catalog\Catalog;
use Paloma\Shop\Catalog\CategoryInterface;
use Paloma\Shop\Catalog\ImageInterface;
use Paloma\Shop\Catalog\SearchRequest;
use Paloma\Shop\PalomaClientFactory;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GoogleSitemapGenerator
{
    /**
     * @var PalomaClientFactory
     */
    private $clientFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var LoggerInterface
     */
    private $log;

    public function __construct(PalomaClientFactory $clientFactory, UrlGeneratorInterface $urlGenerator, LoggerInterface $log)
    {
        $this->clientFactory = $clientFactory;
        $this->urlGenerator = $urlGenerator;
        $this->log = $log;
    }

    public function createXml($baseUrl, $channel, $locale): SimpleXMLElement
    {
        $urlComponents = parse_url($baseUrl);

        $context = $this->urlGenerator->getContext();
        $context->setScheme($urlComponents['scheme']);
        $context->setHost($urlComponents['host']);
        $context->setBaseUrl($urlComponents['path'] ?? '');

        $client = $this->clientFactory->create($channel, $locale, '');
        $catalog = new Catalog($client, new GoogleSitemapPricingContextProvider());

        $xml = new SimpleXMLElement('<urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->addAttribute('__:xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        $this->addCategoryUrls($xml, $catalog, $locale, $catalog->getCategories(0));
        $this->addProductUrls($xml, $catalog, $locale);

        return $xml;
    }

    private function addCategoryUrls(SimpleXMLElement $xml, Catalog $catalog, string $locale, array $categories)
    {
        /** @var CategoryInterface $cat */
        foreach ($categories as $cat) {

            try {

                $category = $catalog->getCategory($cat->getCode(), 1);

                $this->addCategoryUrl($xml, $category, $locale);

                $this->addCategoryUrls($xml, $catalog, $locale, $category->getSubCategories());

            } catch (\Exception $e) {
                $this->log->error('Failed to create sitemap entry for category', [
                    'category' => $cat,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function addCategoryUrl(SimpleXMLElement $xml, CategoryInterface $category, string $locale): void
    {
        $url = $this->urlGenerator->generate('paloma_catalog_category', [
            'categoryCode' => $category->getCode(),
            'categorySlug' => $category->getSlug(),
            '_locale' => $locale,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $this->addUrl($xml, $url, $category->getModified());
    }

    private function addProductUrls(SimpleXMLElement $xml, Catalog $catalog, string $locale, $page = 0)
    {
        $results = $catalog->search(new SearchRequest(null, null, [], false, $page, 20));

        foreach ($results->getContent() as $product) {

            $url = $this->urlGenerator->generate('paloma_catalog_product', [
                'itemNumber' => $product->getItemNumber(),
                'productSlug' => $product->getSlug(),
                '_locale' => $locale,
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $this->addUrl($xml, $url, $product->getModified(), 'daily', $product->getFirstImage());
        }

        if (!$results->isLast()) {
            $this->addProductUrls($xml, $catalog, $locale, $page + 1);
        }
    }

    private function addUrl(SimpleXMLElement $xml, string $url, \DateTime $modifiedDate = null, $changefreq = 'daily', ImageInterface $image = null)
    {
        $urlXml = $xml->addChild('url');
        $urlXml->addChild('loc', $url);
        if ($modifiedDate) {
            $urlXml->addChild('lastmod', $modifiedDate->format('Y-m-d'));
        }
        $urlXml->addChild('changefreq', $changefreq);

        if ($image && count($image->getSources()) > 0) {
            $urlXml
                ->addChild('__:image:image')
                ->addChild('__:image:loc', array_values($image->getSources())[0]->getUrl());
        }
    }
}