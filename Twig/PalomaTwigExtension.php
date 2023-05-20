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
    private UrlGeneratorInterface $generator;

    private RequestStack $requestStack;

    public function __construct(UrlGeneratorInterface $generator, RequestStack $requestStack)
    {
        $this->generator = $generator;
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('price', [$this, 'price'], ['is_safe' => ['html']]),
            new TwigFunction('product_path', [$this, 'productPath']),
            new TwigFunction('product_url', [$this, 'productUrl']),
            new TwigFunction('category_path', [$this, 'categoryPath']),
            new TwigFunction('category_url', [$this, 'categoryUrl']),
            new TwigFunction('current_path', [$this, 'currentPath']),
        ];
    }

    public function price($object): string
    {
        if ($object instanceof ProductInterface) {
            return $this->productPrice($object);
        }

        return $this->priceStr((string)$object);
    }

    protected function productPrice(ProductInterface $product): string
    {
        $basePrice = $product->getBasePrice();
        $originalPrice = $product->getOriginalBasePrice();

        return $this->priceStr($basePrice, $originalPrice);
    }

    protected function priceStr($basePrice, $originalPrice = null): string
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

    public function productPath(ProductInterface $product, CategoryReferenceInterface $category = null): string
    {
        return $this->generateProductRoute($product, $category);
    }

    public function productUrl(ProductInterface $product, CategoryReferenceInterface $category = null): string
    {
        return $this->generateProductRoute($product, $category, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function generateProductRoute(ProductInterface $product, CategoryReferenceInterface $category = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        if ($category) {

            return $this->generator->generate('paloma_catalog_category_product', [
                'categorySlug' => $category->getSlug(),
                'categoryCode' => $category->getCode(),
                'productSlug' => $product->getSlug(),
                'itemNumber' => $product->getItemNumber(),
            ], $referenceType);
        }

        return $this->generator->generate('paloma_catalog_product', [
            'productSlug' => $product->getSlug(),
            'itemNumber' => $product->getItemNumber(),
        ], $referenceType);
    }

    public function categoryPath(CategoryReferenceInterface $category, array $options = []): string
    {
        return $this->generateCategoryRoute($category, $options);
    }

    public function categoryUrl(CategoryReferenceInterface $category, array $options = []): string
    {
        return $this->generateCategoryRoute($category, $options, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function generateCategoryRoute(CategoryReferenceInterface $category, array $options = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        $includeQueryParams = $options['include_query_params'] ?? false;

        $params = [
            'categorySlug' => $category->getSlug(),
            'categoryCode' => $category->getCode(),
        ];

        if ($includeQueryParams) {
            $request = $this->requestStack->getMainRequest();
            $params = $params + $request->query->all();
        }

        return $this->generator->generate('paloma_catalog_category', $params, $referenceType);
    }

    /**
     * Creates the path for the current route.
     * The given parameters are merged with the current route parameters.
     *
     * @param array $parameters
     * @return string
     */
    public function currentPath(array $parameters = []): string
    {
        $request = $this->requestStack->getMainRequest();

        $route = $request->attributes->get('_route');

        $routeParams = array_merge(
            $request->attributes->get('_route_params'),
            $request->query->all(),
            $parameters
        );

        return $this->generator->generate($route, $routeParams);
    }
}