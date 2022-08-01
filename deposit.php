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
use Shojibflamon\PayseraAssignment\Service\CsvFileProcessUpdate;
use Shojibflamon\PayseraAssignment\Service\FileData;

$input = [
    '2014-12-31,4,private,withdraw,1200.00,EUR',
    '2015-01-01,4,private,withdraw,1000.00,EUR',
    '2016-01-05,4,private,withdraw,1000.00,EUR',
    '2016-01-05,1,private,deposit,200.00,EUR',
];

//Dump::ddd(json_encode($input));
//
//$transactions = new FileData($input);
////Dump::dd($transactions);
//$processFile = new CsvFileProcessNew($transactions);
//Dump::ddd($processFile);



//$file = 'input.csv';
//$csv = new CsvFile($file);
//Dump::dd($csv);

//$transactions = $csv->getData();
$transactions = new FileData($input);

//Dump::dd($transactions);

$processFile = new CsvFileProcessNew($transactions);
//Dump::ddd($processFile);

$transaction = $processFile->convertObject();
echo '################ $transaction = $processFile->convertObject();' . PHP_EOL;
//Dump::dd($transaction);
echo '################ $transaction = $processFile->convertObject();' . PHP_EOL;
$calculateCommission = new CalculateCommission($transaction);
//Dump::dd($transaction);
$fees = $calculateCommission->process();

Dump::ddd($fees);



$date = new DateOperation('2014-12-31');
$user = new User(4, 'private');
$amount = new Amount(1200, 'EUR');
$operationType = new OperationType('withdraw');

$transaction1 = new Transaction($date, $user, $operationType, $amount);
$abc = new CsvFileProcessUpdate();
$abc->pushTransaction($transaction1);
//$abc->setTransactions($transaction1);
echo '################ $transaction1 = new Transaction($date, $user, $operationType, $amount);' . PHP_EOL;
//Dump::ddd($abc);
echo '################ $transaction1 = new Transaction($date, $user, $operationType, $amount);' . PHP_EOL;

$calculateCommission = new CalculateCommission($abc);
$fees = $calculateCommission->process();

Dump::ddd($fees);


/*
 * FileInterface
 *  CsvFile
 *  $csvFile = new CsvFile('input.csv')
 *  JsonFile
 *  $jsonFile = new CsvFile('input.json')
 *
 *
 *
 * */




