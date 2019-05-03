<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;

class SearchController extends AbstractPalomaController
{
    public function view()
    {
        return $this->render('@PalomaShop/catalog/search/view.html.twig', [
        ]);
    }
}