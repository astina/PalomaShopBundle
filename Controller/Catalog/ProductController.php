<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\Shop\Catalog\ProductInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\PalomaSerializer;

class ProductController extends AbstractPalomaController
{
    public function view(string $productSlug, string $itemNumber, PalomaSerializer $serializer)
    {
        $product = $this->catalog->getProduct($itemNumber);

        // If slug does not match, redirect to proper URL
        if ($product->getSlug() !== $productSlug) {
            return $this->redirectToProduct($product, true);
        }

        return $this->renderPalomaView('catalog/product/view.html.twig', [
            'product' => $product,
            'product_json' => $serializer->serialize($product),
        ]);
    }

    public function viewInCategory(string $categorySlug, string $categoryCode,
                                   string $productSlug, string $itemNumber,
                                   PalomaSerializer $serializer)
    {
        $product = $this->catalog->getProduct($itemNumber);

        if (!$this->isProductInCategory($product, $categoryCode)) {
            return $this->redirectToProduct($product);
        }

        $category = $this->catalog->getCategory($categoryCode);

        // If slugs do not match, redirect to proper URL
        if ($product->getSlug() !== $productSlug
            || $category->getSlug() !== $categorySlug) {
            return $this->redirectToProductAndCategory($product, $category, true);
        }

        return $this->renderPalomaView('catalog/product/view.html.twig', [
            'product' => $product,
            'category' => $category,
            'product_json' => $serializer->serialize($product),
        ]);
    }

    private function isProductInCategory(ProductInterface $product, string $categoryCode)
    {
        foreach ($product->getCategories() as $category) {
            if ($category->getCode() === $categoryCode) {
                return true;
            }
        }

        return false;
    }
}