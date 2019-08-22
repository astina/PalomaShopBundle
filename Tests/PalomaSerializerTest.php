<?php

namespace Paloma\ShopBundle\Tests;

use Paloma\Shop\Catalog\Product;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PalomaSerializerTest extends TestCase
{
    public function testSerializeWithInclude()
    {
        $serializer = new Serializer(
            [
                new ObjectNormalizer(),
            ]
        );

        $palomaSerializer = new PalomaSerializer($serializer);

        $product = $this->createProduct();

        $json = $palomaSerializer->serialize(
            $product,
            [
                'include' => [
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
                    ]
                ],
            ]);

        $data = json_decode($json, true);

        $this->assertArrayHasKey('itemNumber', $data);
        $this->assertArrayNotHasKey('attributes', $data);
        $this->assertArrayHasKey('sources', $data['firstImage']);
        $this->assertArrayHasKey('small', $data['firstImage']['sources']);
    }

    public function testSerializeWithExclude()
    {
        $serializer = new Serializer(
            [
                new ObjectNormalizer(),
            ]
        );

        $palomaSerializer = new PalomaSerializer($serializer);

        $product = $this->createProduct();

        $json = $palomaSerializer->serialize(
            $product,
            [
                'exclude' => [
                    'description',
                    'variants' => [
                        'pricing'
                    ],
                ],
            ]);

        $data = json_decode($json, true);

        $this->assertArrayHasKey('itemNumber', $data);
        $this->assertArrayNotHasKey('description', $data);
        $this->assertArrayNotHasKey('pricing', $data['variants'][0]);
    }

    /**
     * @return Product
     */
    private function createProduct(): Product
    {
        return new Product([
            'itemNumber' => 'itemNumber',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'shortDescription' => 'shortDescription',
            'variants' => [
                [
                    'sku' => 'sku',
                    'name' => 'name',
                    'pricing' => [
                        'currency' => 'currency',
                        'grossPriceFormatted' => 'grossPriceFormatted',
                        'originalGrossPriceFormatted' => 'originalGrossPriceFormatted',
                        'taxes' => [
                            'vat' => [
                                'rateFormatted' => 'rateFormatted'
                            ]
                        ]
                    ],
                    'options' => [
                        [
                            'option' => 'option',
                            'label' => 'label',
                            'value' => 'value',
                        ]
                    ],
                    'attributes' => [
                        'type' => [
                            'type' => 'type',
                            'label' => 'label',
                            'value' => 'value',
                            'display' => 'product',
                        ],
                        'hidden' => [
                            'type' => 'hidden',
                            'label' => 'label',
                            'value' => 'value',
                            'display' => 'none',
                        ]
                    ],
                    'images' => [
                        [
                            'name' => 'name',
                            'sources' => [
                                [
                                    'size' => 'size',
                                    'url' => 'url',
                                ]
                            ]
                        ]
                    ],
                    'availability' => [
                        'available' => true,
                        'availableFrom' => null,
                        'availableStock' => 10,
                    ],
                ]
            ],
            'master' => [
                'pricing' => [
                    'currency' => 'currency',
                    'grossPriceFormatted' => 'grossPriceFormatted',
                    'originalGrossPriceFormatted' => 'originalGrossPriceFormatted',
                    'taxes' => [
                        'vat' => [
                            'rateFormatted' => 'rateFormatted'
                        ]
                    ]
                ],
                'attributes' => [
                    'type' => [
                        'type' => 'type',
                        'label' => 'label',
                        'value' => 'value',
                        'display' => 'product',
                    ],
                    'hidden' => [
                        'type' => 'hidden',
                        'label' => 'label',
                        'value' => 'value',
                        'display' => 'none',
                    ]
                ],
                'images' => [
                    [
                        'name' => 'name',
                        'sources' => [
                            [
                                'size' => 'small',
                                'url' => 'url',
                            ],
                        ]
                    ]
                ],
                'availability' => [
                    'available' => true,
                    'availableFrom' => null,
                    'availableStock' => 10,
                ],
            ],
            'created' => '2019-07-18T14:54:40.036+0000',
            'modified' => '2019-07-18T14:54:40.036+0000',
        ]);
    }
}