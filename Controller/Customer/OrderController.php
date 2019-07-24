<?php

namespace Paloma\ShopBundle\Controller\Customer;

use Paloma\Shop\Customers\CustomersInterface;
use Paloma\ShopBundle\Controller\AbstractPalomaController;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractPalomaController
{
    public function invoicePdf(string $orderNumber, CustomersInterface $customers): Response
    {
        return new Response(null, 501);
    }
}