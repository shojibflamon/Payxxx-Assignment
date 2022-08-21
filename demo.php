<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';

use Shojibflamon\PayxxxxAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayxxxxAssignment\Helper\Dump;
use Shojibflamon\PayxxxxAssignment\Service\CsvFile;
use Shojibflamon\PayxxxxAssignment\Service\CsvFileProcess;

$file = 'input.csv';

$csv = new CsvFile($file);
$transactions = $csv->getData();
$processFile = new CsvFileProcess($transactions);
$transactionFactory = $processFile->parseStringFromCsv()->transformation();
$calculateCommission = new CalculateCommission($transactionFactory);

foreach ($calculateCommission->process() as $fee){
    echo $fee . PHP_EOL;
}