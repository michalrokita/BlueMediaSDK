<?php


namespace michalrokita\BlueMediaSDK\DataTypes;


class BMNotification
{
    /**
     * @var string|null
     */
    private $serviceId;

    /**
     * @var array|null
     */
    private $params;

    /**
     * @var string|null
     */
    private $hash;

    /**
     * @var bool
     */
    private $valid;

    /**
     * BMNotification constructor.
     * @param string $serviceId
     * @param array $params
     * @param string $hash
     */
    public function __construct(string $serviceId = null, array $params = null, string $hash = null)
    {
        $this->serviceId = $serviceId;
        $this->params = $params;
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getOrderId(): string
    {
        return $this->params['orderID'];
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     */
    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }
}