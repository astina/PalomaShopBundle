<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Catalog\CategoryInterface;
use Paloma\Shop\Catalog\ProductInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CategoryNotFound;
use Paloma\Shop\Error\ProductNotFound;
use Paloma\ShopBundle\Controller\AbstractPalomaController;

class ProductController extends AbstractPalomaController
{
    public function view()
    {
        return $this->render('@PalomaShop/catalog/product/view.html.twig', [
        ]);
    }

    public function viewInCategory()
    {
        return $this->render('@PalomaShop/catalog/product/view.html.twig', [
        ]);
    }

    public function _viewInCategory(string $categorySlug, string $categoryCode,
                            string $productSlug, string $itemNumber,
                            CatalogInterface $catalog)
    {
        try {
            $product = $catalog->getProduct($itemNumber);
        } catch (BackendUnavailable $e) {
        } catch (ProductNotFound $e) {
        }

        if (!$this->isProductInCategory($product, $categoryCode)) {
            return $this->redirectToProduct($product);
        }

        try {
            $category = $catalog->getCategory($categoryCode);
        } catch (BackendUnavailable $e) {
        } catch (CategoryNotFound $e) {
        }

        // If slugs do not match, redirect to proper URL
        if ($product->getSlug() !== $productSlug
            || $category->getSlug() !== $categorySlug) {
            return $this->redirectToProductAndCategory($product, $category);
        }

        try {
            $categories = $catalog->getCategories(1);
        } catch (BackendUnavailable $e) {
        }

        return $this->render('@PalomaShop/catalog/category/product.html.twig', [
            'category' => $category,
            'categories' => $categories,
            'product' => $product,
        ]);
    }

    private function redirectToProductAndCategory(ProductInterface $product, CategoryInterface $category)
    {
        return $this->redirectToRoute('paloma_category_product', [
            'categorySlug' => $category->getSlug(),
            'categoryCode' => $category->getCode(),
            'productSlug' => $product->getSlug(),
            'itemNumber' => $product->getItemNumber(),
        ],Response::HTTP_MOVED_PERMANENTLY);
    }

    private function redirectToProduct(ProductInterface $product)
    {
        // TODO
    }

    private function isProductInCategory(ProductInterface $product, string $categoryCode)
    {
        return true;
    }
}