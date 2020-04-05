<?php


namespace michalrokita\BlueMediaSDK\Transactions;


use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\Helpers\BMHash;
use michalrokita\BlueMediaSDK\Helpers\UrlGenerator;

class Payment
{
    /**
     * @param Transaction $transaction
     * @return string
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     * @throws \michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException
     */
    public function generatePaymentLink(Transaction $transaction): string
    {
        $config = BMService::getConfig();

        $transactionParams = $transaction->getTransactionParams();
        $transactionParams[] = BMHash::makeWithoutOrdering($transactionParams);

        return UrlGenerator::build($config->getServiceUrl(), $transactionParams);
    }
}