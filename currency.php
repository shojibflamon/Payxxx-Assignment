<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';


use Shojibflamon\PayseraAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\Currency;
use Shojibflamon\PayseraAssignment\Model\CurrencyPrecision;
use Shojibflamon\PayseraAssignment\Provider\PayseraExchangeRateServiceProvider;
use Shojibflamon\PayseraAssignment\Service\CalculateFee;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcess;
use Shojibflamon\PayseraAssignment\Service\CurrencyConverter;

$eur = new Currency('EUR');
$usd = new Currency('USD');

//$eur = new CurrencyPrecision('EUR',2);
//$usd = new CurrencyPrecision('USD',2);

$payseraServiceProvider = new PayseraExchangeRateServiceProvider();
$exchangeRate = $payseraServiceProvider->setExchangeRateSource('static')->getExchangeRate($usd, $eur);
Dump::ddd($exchangeRate);

$currencyConverter = new CurrencyConverter($exchangeRate);
//Dump::ddd($currencyConverter);

$ra = $currencyConverter->convert(10, $usd, $eur);

Dump::ddd($ra);
