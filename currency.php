<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';

use Shojibflamon\PayxxxxAssignment\Helper\Dump;
use Shojibflamon\PayxxxxAssignment\Model\Currency;
use Shojibflamon\PayxxxxAssignment\Provider\PayxxxxExchangeRateServiceProvider;
use Shojibflamon\PayxxxxAssignment\Service\CurrencyConverter;

$EUR = new Currency('EUR');
$USD = new Currency('USD');
$exchangeRateProvider = new PayxxxxExchangeRateServiceProvider();

// STATIC EXCHANGE RATE
$exchangeRate = $exchangeRateProvider->setExchangeRateSource('static')->getExchangeRate($EUR, $USD);

// LIVE EXCHANGE RATE PROVIDED BY : https://developers.---.com/tasks/api/currency-exchange-rates
//$exchangeRate = $exchangeRateProvider->setExchangeRateSource('live')->getExchangeRate($EUR, $USD);

$currencyConverter = new CurrencyConverter($exchangeRate);

Dump::dd($currencyConverter->convert(1, $EUR, $USD));
Dump::dd($currencyConverter->convert(1, $USD, $EUR));
