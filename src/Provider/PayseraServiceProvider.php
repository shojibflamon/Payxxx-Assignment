<?php

namespace Shojibflamon\PayseraAssignment\Provider;

use Shojibflamon\PayseraAssignment\Client\CurlClient;

class PayseraServiceProvider
{
    const PAYSERA_EXCHAGNE_RATE = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

    private $client;

    public function __construct()
    {
        $this->client = new CurlClient();
    }

    public function getExchageRate($sourceCurrency, $targetCurrency) : PaymentServiceResponse
    {
        // SAME CURRENCY
        if ($sourceCurrency->getCode() === $targetCurrency->getCode()) {
            return new PaymentServiceResponse($sourceCurrency, $targetCurrency, 1);
        }

        // INVERSE CURRENCY
        if ('EUR' === $targetCurrency->getCode()) {
            $revertExchangeRate = $this->getExchageRate($targetCurrency, $sourceCurrency);
            return new PaymentServiceResponse($sourceCurrency, $targetCurrency, 1 / $revertExchangeRate->getRatio());
        }

        $exchangeRateResponse = $this->client->setUrl(self::PAYSERA_EXCHAGNE_RATE)->setMethod('GET')->callApi()->getResponseDecode(true);

        $dateString = date('Y-m-d');

        if ($exchangeRateResponse['date'] === $dateString) {
            $exchangeRates = $exchangeRateResponse['rates'];
            return new PaymentServiceResponse($sourceCurrency, $targetCurrency, $exchangeRates[$targetCurrency->getCode()]);
        }
    }
}