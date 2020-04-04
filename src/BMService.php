<?php


namespace michalrokita\BlueMediaSDK;


use michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException;
use michalrokita\BlueMediaSDK\Notifications\Receiver;

class BMService
{
    /**
     * @var Receiver
     */
    private $receiver;
    /**
     * @var BMConfig
     */
    private static $config;

    public function __construct(BMConfig $config)
    {
        self::$config = $config;
    }

    public function setConfig(BMConfig $config): BMService
    {
        self::$config = $config;

        return $this;
    }

    /**
     * @return BMConfig
     * @throws ConfigWasNotSetException
     */
    public static function getConfig(): BMConfig
    {
        if (self::$config) {
            return self::$config;
        }

        throw new ConfigWasNotSetException();
    }

    public function receiver(): Receiver
    {
        if ($this->receiver) {
            return $this->receiver;
        }

        return $this->receiver = new Receiver();
    }
}