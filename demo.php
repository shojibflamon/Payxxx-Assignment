<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';

use Shojibflamon\PayseraAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Service\CsvFile;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcess;

$file = 'input.csv';

$csv = new CsvFile($file);
$transactions = $csv->getData();
$processFile = new CsvFileProcess($transactions);
$transactionFactory = $processFile->parseStringFromCsv()->transformation();
$calculateCommission = new CalculateCommission($transactionFactory);
$fees = $calculateCommission->process();
Dump::ddd($fees);