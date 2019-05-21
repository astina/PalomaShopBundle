<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\Shop\Catalog\SearchFilter;
use Paloma\Shop\Catalog\SearchRequest;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\PalomaSerializer;

class HomeController extends AbstractPalomaController
{
    public function view(PalomaSerializer $serializer)
    {
        $searchRequest = new SearchRequest(
            null,
            null,
            [
                new SearchFilter(
                    'master.attributes.sales_total.value',
                    [],
                    1
                )
            ],
            false,
            0,
            12,
            'attribute.sales_total',
            true
        );

        $searchModel = [
            'request' => $searchRequest,
        ];

        return $this->renderPalomaView('catalog/home/view.html.twig', [
            'top_sellers_search_json' => $serializer->serialize($searchModel),
        ]);
    }
}