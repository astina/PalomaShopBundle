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

class SearchResource
{
    public function search(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
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

            return $serializer->toJsonResponse($product);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), 400);
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