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
     * @param bool $orderCheck
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public static function verify(BMNotification $notification, bool $orderCheck = true): void
    {
        if ($orderCheck) {
            $hash = self::make($notification->getParams());
        } else {
            $hash = self::makeWithoutOrdering($notification->getParams());
        }

        $valid = hash_equals($hash, $notification->getHash());

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
                $key = strtolower($key);
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
        $lowerParams = [];
        foreach ($params as $key => $value) {
            $lowerParams[strtolower($key)] = $value;
        }
        $this->params = $lowerParams;
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