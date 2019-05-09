<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Catalog\SearchRequest;
use Paloma\Shop\Catalog\SearchSuggestions;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidInput;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class SearchResource
{
    const PRODUCT_PAGE_DEFAULT_INCLUDE = [
        'content' => [
            'itemNumber',
            'slug',
            'name',
            'basePrice',
            'originalBasePrice',
            'shortDescription',
            'firstImage' => [
                'sources' => [
                    'small',
                ],
            ],
            'attributes' => [
                'brand',
            ],
        ],
        'totalElements',
        'totalPages',
        'number',
        'first',
        'last',
        'sort',
    ];

    public function search(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request, RouterInterface $router)
    {
        if ($request->getMethod() == 'POST') {
            $searchRequest = $serializer->deserialize($request->getContent(), SearchRequest::class);
        } else {
            $searchRequest = new SearchRequest(
                $request->get('category'),
                $request->get('query')
            );
        }

        try {

            $product = $catalog->search($searchRequest);

            return $serializer->toJsonResponse($product, [
                'include' => self::PRODUCT_PAGE_DEFAULT_INCLUDE,
                'extend' => [
                    '$' => [
                        '_links' => function() use ($router) {
                            return [
                                'product' => [
                                    'href' => $router->generate('paloma_catalog_product', [
                                        'itemNumber' => '__itemNumber__',
                                        'productSlug' => '__productSlug__',
                                    ]),
                                    'templated' => true,
                                ],
                                'category_product' => [
                                    'href' => $router->generate('paloma_catalog_category_product', [
                                        'categorySlug' => '__categorySlug__',
                                        'categoryCode' => '__categoryCode__',
                                        'itemNumber' => '__itemNumber__',
                                        'productSlug' => '__productSlug__',
                                    ]),
                                    'templated' => true,
                                ],
                                'get' => [
                                    'href' => $router->generate('paloma_api_products_get', [
                                        'itemNumber' => '__itemNumber__',
                                    ]),
                                    'templated' => true,
                                ],
                            ];
                        },
                    ]
                ],
            ]);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        }
    }

    public function suggestions(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $query = trim($request->get('query', ''));

        if ($query === '') {
            return $serializer->toJsonResponse(new SearchSuggestions([]));
        }

        try {

            $suggestions = $catalog->getSearchSuggestions($query);

            return $serializer->toJsonResponse($suggestions);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }
}