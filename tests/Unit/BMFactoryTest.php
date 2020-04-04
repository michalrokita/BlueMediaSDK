<?php

namespace Tests\Unit;

use michalrokita\BlueMediaSDK\BMFactory;
use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException;

class BMFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCannotBeBuiltFromInvalidUrl(): void
    {
        $url = 'wrong_url..com';
        $serviceId = 'test';
        $sharedKey = 'test';

        $this->expectException(StringIsNotValidUrlException::class);

        BMFactory::build($url, $serviceId, $sharedKey);
    }

    public function testCanBeBuiltFromValidUrl(): void
    {
        $url = 'https://correct-url.com';
        $serviceId = 'test';
        $sharedKey = 'test';

        $this->assertInstanceOf(
            BMService::class,
            BMFactory::build($url, $serviceId, $sharedKey)
        );
    }

}