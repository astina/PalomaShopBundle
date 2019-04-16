<?php

namespace Paloma\ShopBundle\Controller;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CategoryNotFound;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryResource
{
    public function list(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $depth = max(0, (int) $request->get('depth', 0));

        try {

            $categories = $catalog->getCategories($depth);

            return $serializer->toJsonResponse($categories);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function get(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $categoryCode = (string) $request->get('code');
        $depth = max(0, (int) $request->get('depth', 0));

        if (!$categoryCode) {
            return new Response('Parameter `code` missing', 400);
        }

        try {

            $category = $catalog->getCategory($categoryCode, $depth);

            return $serializer->toJsonResponse($category);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (CategoryNotFound $e) {
            return new Response(null, 404);
        }
    }
}