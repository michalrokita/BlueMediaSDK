<?php


namespace michalrokita\BlueMediaSDK;


use michalrokita\BlueMediaSDK\DataTypes\Url;

class BMFactory
{

    /**
     * BMFactory build function.
     * @param string $serviceUrl Adres systemu płatności ustalony przy rejestracji usługi w Blue Media
     * @param string $serviceId Identyfikator Serwisu Partnera, nadawany wtrakcie rejestracji usługi.
     * @param string $sharedKey Klucz współdzielony
     * @return BMService
     * @throws Exceptions\StringIsNotValidUrlException
     */
    public static function build(string $serviceUrl, string $serviceId, string $sharedKey): BMService
    {
        $config = new BMConfig(Url::fromString($serviceUrl), $serviceId, $sharedKey);
        return new BMService($config);
    }
}