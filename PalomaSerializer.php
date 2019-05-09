<?php

namespace Paloma\ShopBundle;

use Closure;
use InvalidArgumentException;
use Paloma\Shop\Common\SelfNormalizing;
use Paloma\Shop\Utils\ArrayUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class PalomaSerializer
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($object, $options = [])
    {
        if ($object instanceof SelfNormalizing) {
            $data = $object->_normalize();
        } else {
            $data = $this->serializer->normalize($object);
        }

        if (isset($options['include'])) {
            $data = $this->include($data, $options['include']);
        }

        // TODO recursive
        if (isset($options['exclude'])) {
            foreach (array_keys($data) as $field) {
                if (in_array($field, $options['exclude'])) {
                    unset($data[$field]);
                }
            }
        }

        if (isset($options['extend'])) {
            $data = $this->extend($data, $options['extend']);
        }

        return json_encode($data);
    }

    public function deserialize(string $json, $targetClass)
    {
        return $this->serializer->deserialize($json, $targetClass, 'json');
    }

    public function toJsonResponse($object, $options = [])
    {
        $status = isset($options['status']) ? (int) $options['status']: 200;

        return new Response($this->serialize($object, $options), $status, [
            'content-type' => 'application/json; charset=utf-8'
        ]);
    }

    public function toArray(string $json)
    {
        return json_decode($json, true);
    }

    private function include(array $data, array $include): array
    {
        $fields = [];
        foreach ($include as $key => $value) {
            if (is_numeric($key)) {
                $fields[] = $value;
            } else {
                $fields[] = $key;
            }
        }

        $keys = array_keys($data);

        if (count($keys) === 0) {
            return $data;
        }

        // Are we dealing with a list?
        if (!is_string($keys[0])) {

            foreach ($data as &$elem) {

                foreach (array_keys($elem) as $field) {

                    if (!in_array($field, $fields)) {
                        unset($elem[$field]);
                    }

                    if (isset($elem[$field]) && isset($include[$field])) {
                        $elem[$field] = $this->include($elem[$field], $include[$field]);
                    }
                }
            }

            return $data;
        }

        foreach ($keys as $field) {

            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }

            if (isset($data[$field]) && isset($include[$field])) {
                $data[$field] = $this->include($data[$field], $include[$field]);
            }
        }

        return $data;
    }

    private function extend(array $data, array $extend)
    {
        // special case: root element
        if (isset($extend['$'])) {
            $data = $this->applyExtension($data, $extend['$']);
        }

        foreach (array_keys($data) as $fieldName) {
            if (isset($extend[$fieldName])) {
                $data[$fieldName] = $this->applyExtension($data[$fieldName], $extend[$fieldName]);
            }
        }

        return $data;
    }

    private function applyExtension($data, $extension)
    {
        if (ArrayUtils::isList($data)) {

            foreach ($data as &$elem) {
                $elem = $this->applyExtension2($elem, $extension);
            }

            return $data;
        }

        return $this->applyExtension2($data, $extension);
    }

    private function applyExtension2($data, $extension)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Can only extend object fields');
        }

        foreach ($extension as $extensionName => $extensionValue) {
            $data[$extensionName] = $this->resolveExtensionValue($extensionValue, $data);
        }

        return $data;
    }

    private function resolveExtensionValue($value, $elem)
    {
        if ($value instanceof Closure) {
            return $value($elem);
        }

        return $value;
    }
}