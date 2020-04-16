<?php


namespace michalrokita\BlueMediaSDK\Helpers;


use LaLit\Array2XML;

class Response
{
    /**
     * @var string
     */
    private const XML_HEADER = 'Content-type: text/xml; charset=utf-8';

    public static $test = false;

    /**
     * @param array $content
     * @param int $status
     * @return string
     * @throws \Exception
     */
    public static function XML(array $content, int $status = 200): string
    {
        $preparedContent = self::prepareXMLElement($content);
        self::setHeader(self::XML_HEADER);
        return Array2XML::createXML($preparedContent->rootElement, $preparedContent->body)->saveXML();
    }

    private static function prepareXMLElement(array $content): \StdClass
    {
        $return = new \StdClass();
        $return->rootElement = ArrayHelper::arrayLastKey($content);
        $return->body = $content[$return->rootElement];

        return $return;
    }

    private static function setHeader(string $XML_HEADER): void
    {
        if (!self::$test) {
            header($XML_HEADER);
        }
    }
}