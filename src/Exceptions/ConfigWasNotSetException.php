<?php


namespace michalrokita\BlueMediaSDK\Exceptions;


class ConfigWasNotSetException extends \Exception
{
    public function __construct()
    {
        parent::__construct('BM settings were not set before calling this method.');
    }
}