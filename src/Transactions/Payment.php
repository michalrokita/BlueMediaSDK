<?php


namespace michalrokita\BlueMediaSDK\Transactions;


use michalrokita\BlueMediaSDK\BMService;
use michalrokita\BlueMediaSDK\DataTypes\Url;
use michalrokita\BlueMediaSDK\Helpers\BMHash;
use michalrokita\BlueMediaSDK\Helpers\Request;
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
        $hash = BMHash::makeWithoutOrdering($transactionParams);

        $params = [];
        $params['ServiceID'] = $config->getServiceID();
        $params = array_merge($params, $transactionParams);
        $params['Hash'] = $hash;

        return UrlGenerator::build($config->getServiceUrl(), $params);
    }

    /**
     * @param int $gatewayId
     * @return array
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     * @throws \michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException
     */
    public function paymentMethod(int $gatewayId): ?array
    {
        foreach ($this->paymentMethods() as $paymentMethod)
        {
            if ($paymentMethod['gatewayID'] == $gatewayId) {
                return $paymentMethod;
            }
        }

        return null;
    }

    /**
     * @return array
     * @throws \michalrokita\BlueMediaSDK\Exceptions\ConfigWasNotSetException
     * @throws \michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException
     */
    public function paymentMethods(): array
    {
        try {
            $key = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            $key = uniqid('asdfghjkl', true);
        }
        $serviceId = BMService::getConfig()->getServiceId();
        $hash = BMHash::makeWithoutOrdering([$key]);
        $url = Url::fromString('https://pay.bm.pl/paywayList');

        $data = Request::post($url, [
            'ServiceID' => $serviceId,
            'MessageID' => $key,
            'Hash' => $hash
        ]);

        return $data['list']['gateway'];
    }
}