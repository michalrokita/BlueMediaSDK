<?php


namespace michalrokita\BlueMediaSDK\Transactions;


use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\DataTypes\Email;
use michalrokita\BlueMediaSDK\DataTypes\Transactions\Amount;
use michalrokita\BlueMediaSDK\DataTypes\Transactions\Currency;
use michalrokita\BlueMediaSDK\DataTypes\Transactions\Description;
use michalrokita\BlueMediaSDK\DataTypes\Transactions\LinkValidityTime;
use michalrokita\BlueMediaSDK\DataTypes\Transactions\ValidityTime;

class Transaction
{
    /**
     * @var string
     */
    private $orderID;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var int
     */
    private $gatewayID;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var Email
     */
    private $customerEmail;

    /**
     * @var ValidityTime
     */
    private $validityTime;

    /**
     * @var LinkValidityTime
     */
    private $linkValidityTime;

    /**
     * Transaction constructor.
     * @param string $orderId
     * @param float $amount
     */
    public function __construct(string $orderId, float $amount)
    {
        $this->orderID = $orderId;
        $this->amount = new Amount($amount);
    }

    /**
     * @param Description $description
     * @return Transaction
     */
    public function setDescription(Description $description): Transaction
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int $gatewayId
     * @return Transaction
     */
    public function setGatewayId(int $gatewayId): Transaction
    {
        $this->gatewayID = $gatewayId;
        return $this;
    }

    /**
     * @param Currency $currency
     * @return Transaction
     */
    public function setCurrency(Currency $currency): Transaction
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param Email $customerEmail
     * @return Transaction
     */
    public function setCustomerEmail(Email $customerEmail): Transaction
    {
        $this->customerEmail = $customerEmail;
        return $this;
    }

    /**
     * @param ValidityTime $validityTime
     * @return Transaction
     */
    public function setValidityTime(ValidityTime $validityTime): Transaction
    {
        $this->validityTime = $validityTime;
        return $this;
    }

    /**
     * @param LinkValidityTime $linkValidityTime
     * @return Transaction
     */
    public function setLinkValidityTime(LinkValidityTime $linkValidityTime): Transaction
    {
        $this->linkValidityTime = $linkValidityTime;
        return $this;
    }

    /**
     * @return array
     */
    public function getTransactionParams(): array
    {
        $properties = get_object_vars($this);
        $notEmpty = [];

        foreach ($properties as $key => $value) {
            if (isset($value) && $value !== null) {
                $notEmpty[ucfirst($key)] = $value;
            }
        }

        return $notEmpty;
    }

}