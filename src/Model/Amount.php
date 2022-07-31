<?php

namespace Shojibflamon\PayseraAssignment\Model;

use Shojibflamon\PayseraAssignment\Provider\PayseraExchangeRateServiceProvider;
use Shojibflamon\PayseraAssignment\Service\CurrencyConverter;

class Amount
{
    /**
     * @var float
     */
    private float $amount;

    /**
     * @var Currency
     */
    private Currency $operationCurrency;

    /**
     * @var Currency
     */
    private Currency $baseCurrency;

    /**
     * @var
     */
    private $exchangeRate;

     /**
     * @var
     */
    private $exchangeRateSource;

    /**
     * @var CurrencyConverter
     */
    private CurrencyConverter $currencyConverter;

    public CONST EXCHANGE_RATE_API_SOURCE = 'static'; // live|static

    /**
     * @param $amount
     * @param $operationCurrency
     */
    public function __construct($amount, $operationCurrency)
    {
        $this->amount = $amount;
        $this->operationCurrency = new Currency($operationCurrency);
        $this->baseCurrency = new Currency('EUR');
        $this->exchangeRateSource = self::EXCHANGE_RATE_API_SOURCE;
        $this->getExchangeRate();

        $this->currencyConverter = new CurrencyConverter($this->exchangeRate);
    }

    /**
     * @return mixed
     */
    public function getAmount(): float
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

    /**
     * @return void
     */
    public function getExchangeRate(): void
    {
        $this->exchangeRate = (new PayseraExchangeRateServiceProvider())
            ->setExchangeRateSource($this->exchangeRateSource)
            ->getExchangeRate($this->operationCurrency, $this->baseCurrency);
    }

    /**
     * @return CurrencyConverter
     */
    public function getCurrencyConverter(): CurrencyConverter
    {
        return $this->currencyConverter = new CurrencyConverter($this->exchangeRate);
    }

    /**
     * @param $value
     * @param int $decimal
     * @return string
     */
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