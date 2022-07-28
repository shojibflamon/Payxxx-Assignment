<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';


use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\Currency;
use Shojibflamon\PayseraAssignment\Provider\PayseraServiceProvider;
use Shojibflamon\PayseraAssignment\Service\CurrencyConverter;

$eur = new Currency('EUR');
$usd = new Currency('USD');

$payseraServiceProvider = new PayseraServiceProvider();
$exchangeRate = $payseraServiceProvider->getExchageRate($usd, $eur);

$currencyConverter = new CurrencyConverter($exchangeRate);


$ra = $currencyConverter->convert(299, $usd, $eur);

Dump::ddd($ra);
