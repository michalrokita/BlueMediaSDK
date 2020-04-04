<?php


namespace michalrokita\BlueMediaSDK;


use michalrokita\BlueMediaSDK\DataTypes\Url;

class BMConfig
{
    /**
     * @var Url
     */
    private $serviceUrl;
    /**
     * @var string
     */
    private $serviceId;
    /**
     * @var string
     */
    private $sharedKey;

    /**
     * BMConfig constructor.
     * @param Url $serviceUrl
     * @param string $serviceId
     * @param string $sharedKey
     */
    public function __construct(Url $serviceUrl, string $serviceId, string $sharedKey)
    {

        $this->serviceUrl = $serviceUrl;
        $this->serviceId = $serviceId;
        $this->sharedKey = $sharedKey;
    }

    /**
     * @return Url
     */
    public function getServiceUrl(): Url
    {
        return $this->serviceUrl;
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * @return string
     */
    public function getSharedKey(): string
    {
        return $this->sharedKey;
    }
}