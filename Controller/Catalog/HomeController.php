<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\Shop\Catalog\SearchRequest;
use Paloma\ShopBundle\Controller\AbstractPalomaController;

class HomeController extends AbstractPalomaController
{
    public function view()
    {
        return $this->renderPalomaView(
            'catalog/home/view.html.twig',
            $this->homeParams());
    }

    protected function homeParams()
    {
        $searchRequest = new SearchRequest(
            null,
            null,
            [],
            false,
            0,
            12,
            'attribute.sales_total',
            true
        );

        $searchModel = [
            'request' => $searchRequest,
        ];

        return [
            'top_sellers_search_json' => $this->serializer->serialize($searchModel),
        ];
    }
}