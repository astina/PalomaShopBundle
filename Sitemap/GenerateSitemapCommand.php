<?php

namespace Paloma\ShopBundle\Sitemap;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class GenerateSitemapCommand extends Command
{
    /**
     * @var GoogleSitemapGenerator
     */
    private $generator;

    public function __construct(GoogleSitemapGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('paloma:generate-sitemap')
            ->setDescription('Generates sitemap.xml from all categories and products for a given channel and locale')
            ->addArgument('channel', InputArgument::REQUIRED, 'Paloma shop channel')
            ->addArgument('locale', InputArgument::REQUIRED, 'Locale to be used for URL generation')
            ->addArgument('baseUrl', InputArgument::REQUIRED, 'Base URL for all category and product URLs')
            ->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'Path where the generated sitemap should be stored (default: ./public)', 'public')
            ->addOption('fileName', 'f', InputOption::VALUE_REQUIRED, 'Filename of the sitemap (default is sitemap_[channel]_[locale].xml)')
            ->addOption('sitemapIndex', 'i', InputOption::VALUE_REQUIRED, 'If specified, the generated sitemap will be added to the sitemap index')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument('channel');
        $locale = $input->getArgument('locale');
        $baseUrl = $input->getArgument('baseUrl');
        $path = $input->getOption('path');
        $fileName = $input->getOption('fileName');
        $sitemapIndex = $input->getOption('sitemapIndex');

        $file = $fileName ?? 'sitemap_' . $channel . '_' . $locale . '.xml';

        $xml = $this->generator->createXml($baseUrl, $channel, $locale);

        $doc = new \DOMDocument();
        $doc->loadXML($this->addXmlCharset($xml->asXML()));

        $doc->formatOutput = true;
        $doc->save($path . '/' . $file);

        if ($sitemapIndex) {
            $this->updateSitemapIndex($sitemapIndex, $path, $file, $baseUrl);
        }
    }

    private function updateSitemapIndex(string $sitemapIndex, string $path, string $file, string $baseUrl)
    {
        $sitemapIndexFile = $path . '/' . $sitemapIndex;
        $sitemapUrl = $baseUrl . '/' . $file; // TODO path + file to url

        $xml = new \SimpleXMLElement('<sitemapindex/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        if (file_exists($sitemapIndexFile)) {
            $xml = new \SimpleXMLElement(file_get_contents($sitemapIndexFile));
        }

        $sitemapElem = null;
        foreach ($xml->sitemap as $sm) {
            if (('' . $sm->loc) === $sitemapUrl) {
                $sitemapElem = $sm;
            }
        }

        if (!$sitemapElem) {
            $sitemapElem = $xml->addChild('sitemap');
            $sitemapElem->addChild('loc', $sitemapUrl);
        }

        $sitemapElem->lastmod =  date('Y-m-d\TH:i:s\Z', filemtime($path . '/' . $file));

        $doc = new \DOMDocument();
        $doc->loadXML($this->addXmlCharset($xml->asXML()));

        $doc->formatOutput = true;
        $doc->save($sitemapIndexFile);
    }

    private function addXmlCharset($xml)
    {
        return str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
    }
}