<?php


namespace michalrokita\BlueMediaSDK\Exceptions;


class NotificationNotReceivedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No notification has been received.');
    }
}