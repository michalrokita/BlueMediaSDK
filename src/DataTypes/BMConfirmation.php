<?php


namespace michalrokita\BlueMediaSDK\DataTypes;


use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\Helpers\BMHash;

class BMConfirmation
{
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var string
     */
    private $hash;
    /**
     * @var array
     */
    private $params;


    /**
     * BMConfirmation constructor.
     * @param string $orderId
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
        $this->params = $this->generateParams();
        $this->hash = $this->generateHash();
    }

    /**
     * @return array
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    private function generateConfirmationArray() : array
    {
        $config = BMService::getConfig();

        return [
            'confirmationList' => [
                'serviceID' => $config->getServiceId(),
                'transactionsConfirmations' => [
                    'transactionConfirmed' => $this->params
                ],
                'hash' => $this->hash
            ]
        ];
    }

    /**
     * @return array
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    public function toArray(): array
    {
        return $this->generateConfirmationArray();
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     */
    private function generateHash(): string
    {
        return BMHash::makeWithoutOrdering($this->params);
    }

    private function generateParams(): array
    {
        return [
            'orderID' => $this->orderId,
            'confirmation' => 'CONFIRMED'
        ];
    }
}