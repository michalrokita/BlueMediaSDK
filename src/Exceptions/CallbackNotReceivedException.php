<?php


namespace michalrokita\BlueMediaSDK\Exceptions;


class CallbackNotReceivedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No callback has been received.');
    }
}