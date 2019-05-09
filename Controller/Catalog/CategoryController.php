<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\Shop\Catalog\CategoryInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractPalomaController
{
    public function view($categorySlug, $categoryCode, Request $request, PalomaSerializer $serializer)
    {
        $category = $this->catalog->getCategory($categoryCode);

        // Redirect to proper URL if slug differs
        if ($category->getSlug() && $categorySlug !== $category->getSlug()) {
            return $this->redirectToCategory($category, true);
        }

        return $this->renderPalomaView('catalog/category/view.html.twig', [
            'category' => $category,
//            'search' => $this->search($request, $category),
            'search_json' => $serializer->serialize($this->searchModel($request, $category)),
            'category_json' => $serializer->serialize($category),
        ]);
    }

    private function searchModel(Request $request, CategoryInterface $category)
    {
        // TODO move into component

        $searchRequest = $this->searchRequest($request, $category);

        // TODO proper model
        $sort = [
            'options' => [
                'position' => [
                    'property' => 'position',
                    'desc' => false,
                    'selected' => $searchRequest->getSort() === 'position',
                ],
                'relevance' => [
                    'property' => 'relevance',
                    'desc' => false,
                    'selected' => $searchRequest->getSort() === 'relevance',
                ],
                'price_asc' => [
                    'property' => 'price',
                    'desc' => false,
                    'selected' => $searchRequest->getSort() === 'price' && !$searchRequest->isOrderDesc(),
                ],
                'price_desc' => [
                    'property' => 'price',
                    'desc' => true,
                    'selected' => $searchRequest->getSort() === 'price' && $searchRequest->isOrderDesc(),
                ]
            ]
        ];

        if ($category) {
            unset($sort['options']['relevance']);
        } else {
            unset($sort['options']['position']);
        }

        foreach ($sort['options'] as $name => $option) {
            if ($option['selected']) {
                $sort['current'] = $name;
            }
        }

        return [
            'request' => $searchRequest,
            'sort' => $sort,
        ];
    }
}