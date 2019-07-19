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
    const OPTIONS_USER = [
        'exclude' => [
            'userId',
            'customerId',
            'customer' => [
                'id',
            ],
        ]
    ];

    const DEFAULT_INCLUDE_PRODUCT_PAGE = [
        'content' => [
            'itemNumber',
            'slug',
            'name',
            'basePrice',
            'originalBasePrice',
            'reductionPercent',
            'shortDescription',
            'firstImage' => [
                'sources' => [
                    'small',
                ],
            ],
            'attributes' => [
                'brand' => [
                    'value',
                ],
                'badge' => [
                    'value',
                ],
            ],
            'mainCategory' => [
                'name',
                'slug',
                'code',
            ]
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