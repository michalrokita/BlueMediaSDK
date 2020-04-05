<?php


namespace michalrokita\BlueMediaSDK\Helpers;


use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\DataTypes\BMNotification;

class BMHash
{
    /**
     * @var array
     */
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
     * @var array
     */
    private $params = [];

    /**
     * @var bool
     */
    private $withoutOrdering = false;

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
        $hash = new self();
        $hash->setParams($params);
        return $hash->assemble();
    }

    /**
     * @param array $params
     * @return string
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public static function makeWithoutOrdering(array $params): string
    {
        $hash = new self();
        $hash->disableOrderCheck();
        $hash->setParams($params);
        return $hash->assemble();
    }

    /**
     * @return string
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    private function assemble(): string
    {
        $config = BMService::getConfig();
        $concat = [$config->getServiceId()];

        if ($this->withoutOrdering) {
            foreach ($this->params as $param) {
                $concat[] = $param;
            }
        } else {
            foreach (self::$paramsOrder as $key) {
                if (isset($this->params[$key]) && $this->params[$key] !== null && $this->params[$key] !== '') {
                    $concat[] = $this->params[$key];
                }
            }
        }

        $concat[] = $config->getSharedKey();

        return self::hash(implode('|', $concat));
    }

    private function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    private function disableOrderCheck(): self
    {
        $this->withoutOrdering = true;
        return $this;
    }

    private static function hash(string $string): string
    {
        return hash('sha256', $string);
    }
}