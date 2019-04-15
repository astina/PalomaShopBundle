<?php

namespace Paloma\ShopBundle\Controller;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\ProductNotFound;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ProductResource
{
    public function get(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string) $request->get('itemNumber');

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', 400);
        }

        try {

            $product = $catalog->getProduct($itemNumber);

            return $serializer->toJsonResponse($product);

        } catch (BackendUnavailable $e) {
            return new Response(null, 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        }
    }

    public function similar(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string) $request->get('itemNumber');

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', 400);
        }

        try {

            $products = $catalog->getSimilarProducts($itemNumber);

            return $serializer->toJsonResponse($products);

        } catch (BackendUnavailable $e) {
            return new Response(null, 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        }
    }

    public function recommended(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string) $request->get('itemNumber');

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', 400);
        }

        try {

            $products = $catalog->getRecommendedProducts($itemNumber);

            return $serializer->toJsonResponse($products);

        } catch (BackendUnavailable $e) {
            return new Response(null, 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        }
    }
}