<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;

class HomeController extends AbstractPalomaController
{
    public function view()
    {
        return $this->renderPalomaView('catalog/home/view.html.twig', []);
    }
}