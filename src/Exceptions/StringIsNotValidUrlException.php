<?php


namespace michalrokita\BlueMediaSDK\Exceptions;


class StringIsNotValidUrlException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Given string is not a valid URL e.g. https://site.com');
    }
}