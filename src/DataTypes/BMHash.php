<?php


namespace michalrokita\BlueMediaSDK\DataTypes;


use michalrokita\BlueMediaSDK\BMService;

class BMHash
{

    private static $paramsOrder = [
        'orderID',
        'remoteID',
        'amount',
        'currency',
        'gatewayID',
        'paymentDate',
        'paymentStatus',
        'paymentStatusDetails'
    ];

    /**
     * @param BMNotification $notification
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public static function verify(BMNotification $notification): void
    {
        $valid = hash_equals(self::make($notification->getParams()), $notification->getHash());

        $notification->setValid($valid);
    }

    /**
     * @param array $params
     * @return string
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public static function make(array $params): string
    {
        $config = BMService::getConfig();
        $concat = [$config->getServiceId()];

        foreach (self::$paramsOrder as $key) {
            if (isset($params[$key]) && $params[$key] !== null && $params[$key] !== '') {
                $concat[] = $params[$key];
            }
        }

        $concat[] = $config->getSharedKey();

        return hash('sha256', implode('|', $concat));
    }
}