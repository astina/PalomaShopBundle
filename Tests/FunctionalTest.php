<?php

namespace Paloma\ShopBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class FunctionalTest extends WebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}