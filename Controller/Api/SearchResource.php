<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Catalog\SearchRequest;
use Paloma\Shop\Catalog\SearchSuggestions;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidInput;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Paloma\ShopBundle\Serializer\SerializationConstants;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class SearchResource
{
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

            $results = $catalog->search($searchRequest);

            return $serializer->toJsonResponse($results, [
                'include' => SerializationConstants::DEFAULT_INCLUDE_PRODUCT_PAGE,
                'extend' => [
                    '$' => [
                        '_links' => $this->createProductLinks($router),
                    ]
                ],
            ]);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        }
    }

    public function suggestions(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request, RouterInterface $router)
    {
        $query = trim($request->get('query', ''));

        if ($query === '') {
            return $serializer->toJsonResponse(new SearchSuggestions([]));
        }

        try {

            $suggestions = $catalog->getSearchSuggestions($query);

            return $serializer->toJsonResponse($suggestions, [
                'extend' => [
                    '$' => [
                        '_links' => $this->createProductLinks($router)
                    ]
                ],
            ]);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    /**
     * @param RouterInterface $router
     * @return \Closure
     */
    private function createProductLinks(RouterInterface $router): \Closure
    {
        return function() use ($router) {
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
                'category' => [
                    'href' => $router->generate('paloma_catalog_category', [
                        'categorySlug' => '__categorySlug__',
                        'categoryCode' => '__categoryCode__',
                    ]),
                    'templated' => true,
                ],
            ];
        };
    }
}