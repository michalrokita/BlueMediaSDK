<?php


namespace michalrokita\BlueMediaSDK\DataTypes\Transactions;


class Amount
{
    /**
     * @var float
     */
    private $amount;
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public function __toString(): string
    {
        return (string)$this->amount;
    }
}