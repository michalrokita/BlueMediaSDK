<?php

namespace Tests\Unit;

use michalrokita\BlueMediaSDK\BMFactory;
use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\Transactions\Callback;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{
    private function prepareGetRequest(): void
    {
        $_GET['ServiceID'] = '2';
        $_GET['OrderID'] = '100';
        //$_GET['Amount'] = '1.50';
        $_GET['Hash'] = '254eac9980db56f425acf8a9df715cbd6f56de3c410b05f05016630f7d30a4ed';
    }

    private function getBMService(): BMService
    {
        $serviceUrl = 'https://test.com';
        $serviceId = '2';
        $sharedKey = '2test2';

        return BMFactory::build($serviceUrl, $serviceId, $sharedKey);
    }

    public function testGetServiceId()
    {
        $this->prepareGetRequest();
        $bmservice = $this->getBMService();

        $this->assertIsString($bmservice->callback()->getServiceId());
    }

    public function testGetOrderId()
    {
        $this->prepareGetRequest();
        $bmservice = $this->getBMService();
        $this->assertIsString($bmservice->callback()->getOrderId());
    }
}
