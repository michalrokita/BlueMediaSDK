<?php

namespace michalrokita\BlueMediaSDK\Helpers;
use GuzzleHttp\Client;
use LaLit\XML2Array;
use michalrokita\BlueMediaSDK\DataTypes\Url;
use michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException;


class Request
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param Url $url
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public static function post(Url $url, array $data)
    {
        $client = (new self())->client;

        $response = $client->request('POST', (string)$url, [
            'form_params' => $data
        ]);

        $body = $response->getBody();
        return XML2Array::createArray((string)$body);
    }
}