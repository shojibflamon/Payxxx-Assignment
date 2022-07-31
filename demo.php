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

$file = 'input.csv';
$processFile = new CsvFileProcess($file);
//Dump::dd($processFile);die();
$transaction = $processFile->convertObject();

$calculateCommission = new CalculateCommission($transaction);
$fees = $calculateCommission->process();

echo 'asdf';
Dump::ddd($fees);die();

