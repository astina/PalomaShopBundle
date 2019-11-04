<?php

namespace Paloma\ShopBundle\Controller\Api;

use Paloma\Shop\Common\Address;
use Paloma\Shop\Common\AddressInterface;
use Paloma\Shop\Error\InvalidInput;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressResource
{
    public function validateAddress(ValidatorInterface $validator, PalomaSerializer $serializer, Request $request)
    {
        /** @var AddressInterface $address */
        $address = $serializer->deserialize($request->getContent(), Address::class);

        $validation = $validator->validate($address);

        if ($validation->count() > 0) {
            return $serializer->toJsonResponse(InvalidInput::ofValidation($validation)->getValidation(), ['status' => 400]);
        }

        return new Response('', 204);
    }

    // TODO endpoints for auto-complete
}