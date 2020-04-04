<?php


namespace michalrokita\BlueMediaSDK\Exceptions;


class InvalidHashException extends \Exception
{
    public function __construct ()
    {
        parent::__construct('Notification was signed by invalid hash.');
    }
}