<?php

namespace Shojibflamon\PayseraAssignment\Model;

use Shojibflamon\PayseraAssignment\Provider\PayseraExchangeRateServiceProvider;
use Shojibflamon\PayseraAssignment\Service\CurrencyConverter;

class Amount
{
    private $amount;
    private Currency $operationCurrency;
    private Currency $baseCurrency;
    private $exchangeRate;
    private $currencyConverter;

    public function __construct($amount, $operationCurrency)
    {
        $this->amount = $amount;
        $this->operationCurrency = new Currency($operationCurrency);
        $this->baseCurrency = new Currency('EUR');
        $this->getExchangeRate();

        $this->currencyConverter = new CurrencyConverter($this->exchangeRate);
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getOperationCurrency(): Currency
    {
        return $this->operationCurrency;
    }

    /**
     * @return Currency
     */
    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }


    public function getExchangeRate()
    {
        $this->exchangeRate = (new PayseraExchangeRateServiceProvider())
            ->setExchangeRateSource('static')
            ->getExchangeRate($this->operationCurrency, $this->baseCurrency);
    }

    /**
     * @return CurrencyConverter
     */
    public function getCurrencyConverter(): CurrencyConverter
    {
        return $this->currencyConverter = new CurrencyConverter($this->exchangeRate);
//        return $this->currencyConverter;
    }





    public function ceiling($value, int $decimal = 0): string
    {
        $offset = 0.5;
        if ($decimal !== 0) {
            $offset /= pow(10, $decimal);
        }

        $final = round($value + $offset, $decimal, PHP_ROUND_HALF_DOWN);
        return number_format($final, $decimal, '.', '');
    }


}