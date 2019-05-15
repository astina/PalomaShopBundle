<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;

class SearchController extends AbstractPalomaController
{
    public function view()
    {
        return $this->renderPalomaView('catalog/search/view.html.twig', []);
    }
}