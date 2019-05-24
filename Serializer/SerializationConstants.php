<?php

namespace Paloma\ShopBundle\Serializer;

class SerializationConstants
{
    const OPTIONS_ORDER_DRAFT = [
        'exclude' => [
            'customer' => [
                'id',
                'userId'
            ]
        ]
    ];
    const OPTIONS_CUSTOMER = [
        'exclude' => [
            'id',
        ]
    ];

    const DEFAULT_INCLUDE_PRODUCT_PAGE = [
        'content' => [
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
            ],
            'attributes' => [
                'brand',
                'badge',
            ],
        ],
        'filterAggregates',
        'totalElements',
        'totalPages',
        'number',
        'first',
        'last',
        'sort',
    ];
}