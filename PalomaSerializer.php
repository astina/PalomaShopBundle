<?php

namespace Paloma\ShopBundle;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PalomaSerializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($object)
    {
        return $this->serializer->serialize($object, 'json');
    }

    public function deserialize(string $json, $targetClass)
    {
        return $this->serializer->deserialize($json, $targetClass, 'json');
    }

    public function toJsonResponse($object, int $status = 200)
    {
        return new Response($this->serialize($object), $status, [
            'content-type' => 'application/json; charset=utf-8'
        ]);
    }

    public function toArray(string $json)
    {
        return json_decode($json, true);
    }
}