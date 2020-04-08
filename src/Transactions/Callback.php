<?php


namespace michalrokita\BlueMediaSDK\Transactions;

use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\DataTypes\BMNotification;
use michalrokita\BlueMediaSDK\Exceptions\CallbackNotReceivedException;
use michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException;
use michalrokita\BlueMediaSDK\Exceptions\InvalidHashException;
use michalrokita\BlueMediaSDK\Helpers\BMHash;
use RuntimeException;

/**
 * Class Callback
 * @package michalrokita\BlueMediaSDK\Transactions
 */
class Callback
{
    /**
     * @var array
     */
    private $dataReceived;

    private $alreadyCaptured;

    public function __construct()
    {
        $this->dataReceived = [];
        $this->alreadyCaptured = false;
    }

    /**
     * @return string
     * @throws CallbackNotReceivedException
     * @throws ConfigWasNotSetException
     * @throws InvalidHashException
     */
    public function getServiceId(): string
    {
        return BMService::getConfig()->getServiceId();
    }

    /**
     * @return string
     * @throws CallbackNotReceivedException
     * @throws ConfigWasNotSetException
     * @throws InvalidHashException
     */
    public function getOrderId(): string
    {
        return $this->getProperty('OrderID');
    }

    /**
     * @return string
     * @throws CallbackNotReceivedException
     * @throws ConfigWasNotSetException
     * @throws InvalidHashException
     */
    public function getAmount(): string
    {
        return $this->getProperty('Amount');
    }

    /**
     * @param string $key
     * @return mixed
     * @throws CallbackNotReceivedException
     * @throws ConfigWasNotSetException
     * @throws InvalidHashException
     */
    private function getProperty(string $key)
    {
        if (!$this->callbackAvailable()) {
            throw new CallbackNotReceivedException();
        }

        if (!isset($this->dataReceived[$key])) {
            throw new RuntimeException("Property '{$key}' not found");
        }

        return $this->dataReceived[$key];
    }

    /**
     * @return bool
     * @throws ConfigWasNotSetException
     * @throws InvalidHashException
     */
    private function callbackAvailable(): bool
    {
        if (!$this->alreadyCaptured) {
            try {
                $this->captureCallback();
            } catch (CallbackNotReceivedException $e) {
                return false;
            }
        }
        return count($this->dataReceived) > 0;
    }

    /**
     * @throws CallbackNotReceivedException
     * @throws ConfigWasNotSetException
     * @throws InvalidHashException
     */
    private function captureCallback(): void
    {
        if (!$this->getAvailable()) {
            throw new CallbackNotReceivedException();
        }

        $callback = $this->createNotificationInstance();
        BMHash::verify($callback);

        if ($callback->isValid()) {
            $this->dataReceived = $callback->getParams();
        } else {
            throw new InvalidHashException();
        }
    }

    private function getAvailable(): bool
    {
        return isset($_GET) && count($_GET) > 0;
    }

    private function createNotificationInstance(): BMNotification
    {
        $serviceId = $_GET['ServiceID'];
        $hash = $_GET['Hash'];
        $params = [];

        foreach ($_GET as $key => $value) {
            if ($key !== 'Hash' && $key !== 'ServiceID') {
                $params[$key] = $value;
            }
        }

        return new BMNotification($serviceId, $params, $hash);
    }


}