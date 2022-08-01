<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';

use Shojibflamon\PayseraAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcess;

$file = 'input.csv';
$processFile = new CsvFileProcess($file);
Dump::dd($processFile);
$transaction = $processFile->convertObject();
Dump::ddd($transaction);
$calculateCommission = new CalculateCommission($transaction);
//Dump::dd($calculateCommission);
$fees = $calculateCommission->process();
Dump::ddd($fees);

