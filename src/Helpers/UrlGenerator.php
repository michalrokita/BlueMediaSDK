<?php


namespace michalrokita\BlueMediaSDK\Helpers;


use michalrokita\BlueMediaSDK\DataTypes\Url;

class UrlGenerator
{
    /**
     * @param string $baseUrl
     * @param array $params
     * @return Url
     * @throws \michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException
     */
    public static function build(string $baseUrl, array $params = []): Url
    {
        if (count($params) > 0) {
            $url = self::attachParams($baseUrl, $params);
        } else {
            $url = $baseUrl;
        }

        return new Url($url);
    }

    private static function attachParams(string $baseUrl, array $params): string
    {
        return $baseUrl . self::buildParamsString($params);
    }

    private static function buildParamsString(array $params): string
    {
        $paramsString = '?';

        foreach ($params as $key => $param) {
            $paramsString .= "{$key}={$param}";
            $paramsString .= ArrayHelper::arrayLastKey($params) === $key ? '' : '&';
        }

        return $paramsString;
    }

}