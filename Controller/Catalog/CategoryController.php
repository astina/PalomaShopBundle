<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractPalomaController
{
    public function view($categorySlug, $categoryCode, Request $request, PalomaSerializer $serializer)
    {
        $category = $this->catalog->getCategory($categoryCode, 1);

        // Redirect to proper URL if slug differs
        if ($category->getSlug() && $categorySlug !== $category->getSlug()) {
            return $this->redirectToCategory($category, true);
        }

        return $this->renderPalomaView('catalog/category/view.html.twig', [
            'category' => $category,
            'search_json' => $serializer->serialize($this->searchModel($request, $category)),
            'category_json' => $serializer->serialize($category),
        ]);
    }
}