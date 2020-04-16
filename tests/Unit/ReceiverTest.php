<?php

namespace Tests\Unit;

use LaLit\XML2Array;
use michalrokita\BlueMediaSDK\BMFactory;
use michalrokita\BlueMediaSDK\DataTypes\BMConfirmation;
use michalrokita\BlueMediaSDK\Exceptions\NotificationNotReceivedException;
use michalrokita\BlueMediaSDK\Helpers\Response;
use PHPUnit\Framework\TestCase;

class ReceiverTest extends TestCase
{

    public function testGetNotificationWhenNoNotificationIsReceived(): void
    {
        $serviceUrl = 'https://test.com';
        $serviceId = '1';
        $sharedKey = '1test1';

        $bmservice = BMFactory::build($serviceUrl, $serviceId, $sharedKey);

        $this->expectException(NotificationNotReceivedException::class);
        $bmservice->receiver()->getNotification();
    }

    public function testGetNotification(): void
    {
        /**
         * Exemplar XML changed to base64 taken from
         * https://bluemedia.pl/storage/app/media/Bluemedia_pl/Dokumenty/system_platnosci_online_obsluga_transakcji.pdf
         */
        $_POST['transactions'] = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHRyYW5zYWN0aW9uTGlzdD4KPHNlcnZpY2V
        JRD4xPC9zZXJ2aWNlSUQ+Cjx0cmFuc2FjdGlvbnM+Cjx0cmFuc2FjdGlvbj4KPG9yZGVySUQ+MTE8L29yZGVySUQ+CjxyZW1vdGVJRD45MTwvcmV
        tb3RlSUQ+CjxhbW91bnQ+MTEuMTE8L2Ftb3VudD4KPGN1cnJlbmN5PlBMTjwvY3VycmVuY3k+CjxnYXRld2F5SUQ+MTwvZ2F0ZXdheUlEPgo8cGF
        5bWVudERhdGU+MjAwMTAxMDExMTExMTE8L3BheW1lbnREYXRlPgo8cGF5bWVudFN0YXR1cz5TVUNDRVNTPC9wYXltZW50U3RhdHVzPgo8cGF5bWV
        udFN0YXR1c0RldGFpbHM+QVVUSE9SSVpFRDwvcGF5bWVudFN0YXR1c0RldGFpbHM+CjwvdHJhbnNhY3Rpb24+CjwvdHJhbnNhY3Rpb25zPgo8aGF
        zaD5hMTAzYmZlNTgxYTkzOGU5YWQ3ODIzOGNmYzY3NGZmYWZkZDZlYzcwY2I2ODI1ZTdlZDVjNDE3ODc2NzFlZmU0PC9oYXNoPgo8L3RyYW5zYWN
        0aW9uTGlzdD4=';

        $serviceUrl = 'https://test.com';
        $serviceId = '1';
        $sharedKey = '1test1';

        $bmservice = BMFactory::build($serviceUrl, $serviceId, $sharedKey);

        $notification = $bmservice->receiver()->getNotification();

        $this->assertIsArray($notification);
    }

    public function testConfirmNotification(): void
    {
        /**
         * Exemplar XML changed to base64 taken from
         * https://bluemedia.pl/storage/app/media/Bluemedia_pl/Dokumenty/system_platnosci_online_obsluga_transakcji.pdf
         */
        $_POST['transactions'] = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHRyYW5zYWN0aW9uTGlzdD4KPHNlcnZpY2V
        JRD4xPC9zZXJ2aWNlSUQ+Cjx0cmFuc2FjdGlvbnM+Cjx0cmFuc2FjdGlvbj4KPG9yZGVySUQ+MTE8L29yZGVySUQ+CjxyZW1vdGVJRD45MTwvcmV
        tb3RlSUQ+CjxhbW91bnQ+MTEuMTE8L2Ftb3VudD4KPGN1cnJlbmN5PlBMTjwvY3VycmVuY3k+CjxnYXRld2F5SUQ+MTwvZ2F0ZXdheUlEPgo8cGF
        5bWVudERhdGU+MjAwMTAxMDExMTExMTE8L3BheW1lbnREYXRlPgo8cGF5bWVudFN0YXR1cz5TVUNDRVNTPC9wYXltZW50U3RhdHVzPgo8cGF5bWV
        udFN0YXR1c0RldGFpbHM+QVVUSE9SSVpFRDwvcGF5bWVudFN0YXR1c0RldGFpbHM+CjwvdHJhbnNhY3Rpb24+CjwvdHJhbnNhY3Rpb25zPgo8aGF
        zaD5hMTAzYmZlNTgxYTkzOGU5YWQ3ODIzOGNmYzY3NGZmYWZkZDZlYzcwY2I2ODI1ZTdlZDVjNDE3ODc2NzFlZmU0PC9oYXNoPgo8L3RyYW5zYWN
        0aW9uTGlzdD4=';

        $serviceUrl = 'https://test.com';
        $serviceId = '1';
        $sharedKey = '1test1';

        $bmservice = BMFactory::build($serviceUrl, $serviceId, $sharedKey);

        $notification = $bmservice->receiver()->getNotification();

        $this->assertIsArray($notification);

        $confirmation = (new BMConfirmation($notification['orderID']))->toArray();

        $this->assertIsArray($confirmation);

        Response::$test = true;

        $xml = Response::XML($confirmation);

        $this->assertIsString($xml);

        $arrayFromXML = XML2Array::createArray($xml);

        $this->assertIsArray($arrayFromXML);
    }
}
