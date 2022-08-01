<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'start.php';

use Shojibflamon\PayseraAssignment\Calculation\CalculateCommission;
use Shojibflamon\PayseraAssignment\Calculation\Transaction;
use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;
use Shojibflamon\PayseraAssignment\Service\CsvFile;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcess;
use Shojibflamon\PayseraAssignment\Service\CsvFileProcessNew;
use Shojibflamon\PayseraAssignment\Service\TransactionFactory;
use Shojibflamon\PayseraAssignment\Service\FileData;

$input = [
    '2014-12-31,4,private,withdraw,1200.00,EUR',
    '2015-01-01,4,private,withdraw,1000.00,EUR',
    '2016-01-05,4,private,withdraw,1000.00,EUR',
    '2016-01-05,1,private,deposit,200.00,EUR',
];
$file = 'input.csv';


$csv = new CsvFile($file);
//Dump::dd($csv);



// Regular Case
$transactions = $csv->getData();
// Test Case
//$transactions = new FileData($input);
//Dump::ddd($transactions);




$processFile = new CsvFileProcess($transactions);


// Regular Case
$transaction = $processFile->parseStringFromCsv()->transformation();
//Dump::ddd($transaction);
// Test Case
//$date = new DateOperation('2014-12-31');
//$user = new User(2, 'business');
//$amount = new Amount(10000, 'EUR');
//$operationType = new OperationType('deposit');
//
//$transactionObj = new Transaction($date, $user, $operationType, $amount);
//$transaction = new TransactionFactory();
//$transaction->pushTransaction($transactionObj);

//Dump::ddd($transaction);




$calculateCommission = new CalculateCommission($transaction);
//Dump::dd($transaction);
$fees = $calculateCommission->process();

Dump::ddd($fees);