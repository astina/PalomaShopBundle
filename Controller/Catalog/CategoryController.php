<?php

namespace Paloma\ShopBundle\Controller\Catalog;

use Paloma\ShopBundle\Controller\AbstractPalomaController;

class CategoryController extends AbstractPalomaController
{
    public function view()
    {
        return $this->render('@PalomaShop/catalog/category/view.html.twig', [
        ]);
    }
}