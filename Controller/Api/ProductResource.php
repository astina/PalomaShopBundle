<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\InvalidInput;
use Paloma\Shop\Error\ProductNotFound;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductResource
{
    public function get(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string)$request->get('itemNumber');

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', 400);
        }

        try {

            $product = $catalog->getProduct($itemNumber);

            return $serializer->toJsonResponse($product, $request->get('_include', [
                'itemNumber',
                'slug',
                'name',
                'basePrice',
                'originalBasePrice',
                'shortDescription',
                'firstImage'
            ]));

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        }
    }

    public function similar(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string)$request->get('itemNumber');

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', ['status' => 400]);
        }

        try {

            $products = $catalog->getSimilarProducts($itemNumber);

            return $serializer->toJsonResponse($products);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        }
    }

    public function recommended(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string)$request->get('itemNumber');

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', 400);
        }

        try {

            $products = $catalog->getRecommendedProducts($itemNumber);

            return $serializer->toJsonResponse($products);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        }
    }

    public function purchasedTogether(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $itemNumber = (string) $request->get('itemNumber');
        $max = (int) $request->get('max', 6);

        if (!$itemNumber) {
            return new Response('Parameter `itemNumber` missing', 400);
        }

        try {

            $products = $catalog->getPurchasedTogether($itemNumber, $max);

            return $serializer->toJsonResponse($products);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (ProductNotFound $e) {
            return new Response(null, 404);
        } catch (InvalidInput $e) {
            return $serializer->toJsonResponse($e->getValidation(), ['status' => 400]);
        }
    }
}