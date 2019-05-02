<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;

class HomeController extends AbstractPalomaController
{
    public function view()
    {
        return $this->render('@PalomaShop/catalog/home/view.html.twig', [
        ]);
    }
}