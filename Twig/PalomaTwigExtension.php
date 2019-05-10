<?php

namespace Paloma\ShopBundle\Twig;

use Paloma\Shop\Catalog\CategoryReferenceInterface;
use Paloma\Shop\Catalog\ProductInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PalomaTwigExtension extends AbstractExtension
{
    private $generator;

    private $requestStack;

    public function __construct(UrlGeneratorInterface $generator, RequestStack $requestStack)
    {
        $this->generator = $generator;
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('price', [$this, 'price'], ['is_safe' => ['html']]),
            new TwigFunction('product_path', [$this, 'productPath']),
            new TwigFunction('category_path', [$this, 'categoryPath']),
            new TwigFunction('current_path', [$this, 'currentPath']),
        ];
    }

    public function price($object)
    {
        if ($object instanceof ProductInterface) {
            return $this->productPrice($object);
        }

        return $this->priceStr((string)$object);
    }

    protected function productPrice(ProductInterface $product)
    {
        $basePrice = $product->getBasePrice();
        $originalPrice = $product->getOriginalBasePrice();

        return $this->priceStr($basePrice, $originalPrice);
    }

    protected function priceStr($basePrice, $originalPrice = null)
    {
        $spacePos = strpos($basePrice, ' ');

        $currency = substr($basePrice, 0, $spacePos);
        $price = substr($basePrice, $spacePos);

        return sprintf('<span class="price">
                <span class="price__currency">%s</span>
                <span class="price__amount">%s</span>
                <span class="price__original">%s</span>
            </span>',
            $currency, $price, $originalPrice);
    }

    public function productPath(ProductInterface $product, CategoryReferenceInterface $category = null)
    {
        if ($category) {

            return $this->generator->generate('paloma_catalog_category_product', [
                'categorySlug' => $category->getSlug(),
                'categoryCode' => $category->getCode(),
                'productSlug' => $product->getSlug(),
                'itemNumber' => $product->getItemNumber(),
            ]);
        }

        return $this->generator->generate('paloma_catalog_product', [
            'productSlug' => $product->getSlug(),
            'itemNumber' => $product->getItemNumber(),
        ]);
    }

    public function categoryPath(CategoryReferenceInterface $category)
    {
        return $this->generator->generate('paloma_catalog_category', [
            'categorySlug' => $category->getSlug(),
            'categoryCode' => $category->getCode(),
        ]);
    }

    /**
     * Creates the path for the current route.
     * The given parameters are merged with the current route parameters.
     *
     * @param array $parameters
     * @return string
     */
    public function currentPath($parameters = [])
    {
        $request = $this->requestStack->getMasterRequest();

        $route = $request->attributes->get('_route');

        $routeParams = array_merge(
            $request->attributes->get('_route_params'),
            $request->query->all(),
            $parameters
        );

        return $this->generator->generate($route, $routeParams, UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}