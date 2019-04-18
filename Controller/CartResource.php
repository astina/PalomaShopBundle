<?php

namespace Paloma\ShopBundle\Controller;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\Shop\Error\BackendUnavailable;
use Paloma\Shop\Error\CartItemNotFound;
use Paloma\Shop\Error\InsufficientStock;
use Paloma\Shop\Error\ProductVariantNotFound;
use Paloma\Shop\Error\ProductVariantUnavailable;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartResource
{
    public function get(CheckoutInterface $checkout, PalomaSerializer $serializer)
    {
        try {

            $cart = $checkout->getCart();

            return $serializer->toJsonResponse($cart);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function addItem(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $params = $serializer->toArray($request->getContent());

        $sku = $params['sku'] ?? null;
        $quantity = $params['quantity'] ?? 1;

        if (!$sku) {
            return new Response('Parameter `sku` missing', 400);
        }

        try {

            $cart = $checkout->addCartItem($sku, $quantity);

            return $serializer->toJsonResponse($cart);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InsufficientStock $e) {
            return new Response('TODO', 400); // TODO
        } catch (ProductVariantNotFound $e) {
            return new Response(null, 404);
        } catch (ProductVariantUnavailable $e) {
            return new Response('TODO', 400); // TODO
        }
    }

    public function updateItem(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $params = $serializer->toArray($request->getContent());

        $itemId = $params['itemId'] ?? null;
        $quantity = $params['quantity'] ?? 1;

        if (!$itemId) {
            return new Response('Parameter `itemId` missing', 400);
        }

        try {

            $cart = $checkout->updateCartItem($itemId, $quantity);

            return $serializer->toJsonResponse($cart);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        } catch (InsufficientStock $e) {
            return new Response('TODO', 400); // TODO
        } catch (ProductVariantUnavailable $e) {
            return new Response('TODO', 400); // TODO
        } catch (CartItemNotFound $e) {
            return new Response(null, 404);
        }
    }

    public function removeItem(CheckoutInterface $checkout, PalomaSerializer $serializer, Request $request)
    {
        $itemId = $request->get('itemId');

        if (!$itemId) {
            return new Response('Parameter `itemId` missing', 400);
        }

        try {

            $cart = $checkout->removeCartItem($itemId);

            return $serializer->toJsonResponse($cart);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }

    public function recommendations(CatalogInterface $catalog, PalomaSerializer $serializer, Request $request)
    {
        $size = min(20, max(1, (int) $request->get('size', 5)));

        try {

            $products = $catalog->getProductsForCart($size);

            return $serializer->toJsonResponse($products);

        } catch (BackendUnavailable $e) {
            return new Response('Service unavailable', 503);
        }
    }
}