<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Paloma\ShopBundle\Serializer\PalomaSerializer;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractPalomaController
{
    public function view(Request $request, PalomaSerializer $serializer)
    {
        $searchModel = $this->searchModel($request);

        return $this->renderPalomaView('catalog/search/view.html.twig', [
            'search_query' => $searchModel['request']->getQuery(),
            'search_json' => $serializer->serialize($searchModel),
        ]);
    }
}