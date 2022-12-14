<?php

namespace Shojibflamon\Tests;

use PHPUnit\Framework\TestCase;
use Shojibflamon\PayxxxxAssignment\Model\Currency;
use Shojibflamon\PayxxxxAssignment\Provider\PayxxxxExchangeRateServiceProvider;
use Shojibflamon\PayxxxxAssignment\Service\CurrencyConverter;

class CurrencyTest extends TestCase
{

    /**
     * @return void
     */
    public function testExchangeRateStatic(): void
    {
        $EUR = new Currency('EUR');
        $USD = new Currency('USD');
        $exchangeRateProvider = new PayxxxxExchangeRateServiceProvider();
        $exchangeRate = $exchangeRateProvider->setExchangeRateSource('static')->getExchangeRate($EUR, $USD);
        $currencyConverter = new CurrencyConverter($exchangeRate);

        $this->assertEquals(2 * 1.1497, $currencyConverter->convert(2, $EUR, $USD));
        $this->assertEquals(2 * (1 / 1.1497), $currencyConverter->convert(2, $USD, $EUR));
    }

    /**
     * @return void
     */
    public function testExchangeRateLive(): void
    {
        $EUR = new Currency('EUR');
        $USD = new Currency('USD');
        $exchangeRateProvider = new PayxxxxExchangeRateServiceProvider();
        $exchangeRate = $exchangeRateProvider->setExchangeRateSource('live')->getExchangeRate($EUR, $USD);
        $currencyConverter = new CurrencyConverter($exchangeRate);

        $this->assertEquals(2 * 1.129031, $currencyConverter->convert(2, $EUR, $USD));
        $this->assertEquals(2 * (1 / 1.129031), $currencyConverter->convert(2, $USD, $EUR));
    }

    /**
     * @return void
     */
    public function testExchangeRateSameCurrency(): void
    {
        $EUR = new Currency('EUR');
        $exchangeRateProvider = new PayxxxxExchangeRateServiceProvider();
        $exchangeRate = $exchangeRateProvider->setExchangeRateSource('live')->getExchangeRate($EUR, $EUR);
        $currencyConverter = new CurrencyConverter($exchangeRate);

        $this->assertEquals(2 * 1, $currencyConverter->convert(2, $EUR, $EUR));
        $this->assertEquals(2 * (1 / 1), $currencyConverter->convert(2, $EUR, $EUR));
    }

}