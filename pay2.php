<?php
include_once 'start.php';
include_once 'currencyConverter.php';


/*class View
{
    public static function dd($data)
    {
        self::dump($data);
        die();
    }

    public static function dump($data)
    {
        echo '<pre>', print_r($data, true), '</pre>';
    }
}*/

interface FileProcessInterface
{
    public function process();
}

class ProcessCsvFile implements FileProcessInterface
{
    public string $file;
    public array $data;

    public function __construct($file)
    {
        $this->file = $file;
        $this->data = [];
    }

    public function process(): array
    {
        $this->data = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $this->parseStringFromCsv();
    }

    public function parseStringFromCsv(): array
    {
        return array_map('str_getcsv', $this->data);
    }
}

class DateOperation
{
    public string $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function getYear()
    {
        return date('Y', strtotime($this->date));
    }
}

class CommissionOperation
{
    public function getCommissionableAmount($amount, $creditLimit): array
    {
        if ($amount >= $creditLimit) {
            $amount -= $creditLimit;
            $creditLimit = 0;
        } else {
            $creditLimit -= $amount;
            $amount = 0;
        }

        return [$amount,$creditLimit];
    }
}


function ceiling($value, int $decimal = 0): string
{
    $offset = 0.5;
    if ($decimal !== 0) {
        $offset /= pow(10, $decimal);
    }

    $final = round($value + $offset, $decimal, PHP_ROUND_HALF_DOWN);
    return number_format($final, $decimal, '.', '');
}

$file = 'input.csv';
$processFile = new ProcessCsvFile($file);
$rows = $processFile->process();
$arrayDb = [];
$commissionFees = [];
//View::dd($rows);

define('USER_TYPE_PRIVATE', 'private');
define('USER_TYPE_BUSINESS', 'business');
define('OPERATION_TYPE_WITHDRAW', 'withdraw');
define('OPERATION_TYPE_DEPOSIT', 'deposit');
define('OPERATION_TYPE_DEPOSIT_RATE', 0.03);
define('OPERATION_TYPE_BUSINESS_WITHDRAW_RATE', 0.5);
define('OPERATION_TYPE_PRIVATE_WITHDRAW_RATE', 0.3);
define('CREDIT_LIMIT_IN_WEEK', 1000);
define('WITHDRAW_LIMIT_IN_WEEK', 3);





foreach ($rows as $key => $row) {

    /*if ($key !== 5){
        continue;
    }*/

    $commissionFee = 0;
    $creditLimit = CREDIT_LIMIT_IN_WEEK;
    $withdrawLimit = WITHDRAW_LIMIT_IN_WEEK;

    list($operationDate, $userId, $userType, $operationType, $operationAmount, $operationCurrency) = $row;

    if ($operationType === OPERATION_TYPE_DEPOSIT) {
        $commissionFee = $operationAmount * OPERATION_TYPE_DEPOSIT_RATE * .01;
    }

    if ($operationType === OPERATION_TYPE_WITHDRAW && $userType === USER_TYPE_BUSINESS) {
        $commissionFee = $operationAmount * OPERATION_TYPE_BUSINESS_WITHDRAW_RATE * .01;
    }

    if ($operationType === OPERATION_TYPE_WITHDRAW && $userType === USER_TYPE_PRIVATE) {
        $year = date('Y', strtotime($operationDate));

        $weekStartDate = date('Y-m-d', strtotime("this week", strtotime($operationDate)));

        if (array_key_exists($userId, $arrayDb)) {
            if (array_key_exists($weekStartDate, $arrayDb[$userId])) {
                $lastTransaction = $arrayDb[$userId][$weekStartDate];
                $creditLimit = $lastTransaction['creditLimit'];
                $withdrawLimit = $lastTransaction['withdrawLimit'];
            }
        }


        // all currency convert to EUR
        $eur = new Currency('EUR');
        $currency = new Currency($operationCurrency);
        $exchangeRateProvider = new EuropeanCentralBankProvider();
        $exchangeRate = $exchangeRateProvider->getExchangeRate($currency , $eur, new DateTime('today'));
        $currencyConverter = new CurrencyConverter($exchangeRate);
        $amountInEuro = $currencyConverter->convert($operationAmount, $currency, $eur);


        if ($amountInEuro >= $creditLimit) {
            $exceededAmount = $amountInEuro - $creditLimit;
            $commissionableAmount = $exceededAmount;
            $creditLimit = 0;
        } else {
            $exceededAmount = 0;
            $commissionableAmount = 0;
            $creditLimit -= $amountInEuro;
        }

        if ($withdrawLimit > 0){
            $withdrawLimit--;
        }

        $arrayDb[$userId][$weekStartDate] = [
            'exceededAmount' => $exceededAmount,
            'commissionableAmount' => $commissionableAmount,
            'creditLimit' => $creditLimit,
            'withdrawLimit' => $withdrawLimit,

        ];

//        View::dump($arrayDb);

        if ($operationCurrency !== 'EUR') {
//            $exchangeRateProvider = new EuropeanCentralBankProvider();
            $exchangeRate = $exchangeRateProvider->getExchangeRate($eur , $currency, new DateTime('today'));
            $currencyConverter = new CurrencyConverter($exchangeRate);
            $commissionableAmount = $currencyConverter->convert($commissionableAmount, $eur, $currency);

        }

        $commissionFee = $commissionableAmount * OPERATION_TYPE_PRIVATE_WITHDRAW_RATE * .01;

    }

    if ($operationCurrency === 'JPY'){
        $commissionFee = ceiling($commissionFee,0);
    }else{
        $commissionFee = ceiling($commissionFee,2);
    }

    array_push($commissionFees, $commissionFee);


}


//View::dump($arrayDb);
View::dd($commissionFees);







/*var lastDay = new DateTime(2009, 12, 31);
var firstDay = new DateTime(2010, 1, 1);

bool isSameWeek = (int)lastDay.DayOfWeek < (int)firstDay.DayOfWeek;*/

$lastDay = new DateTime('2014-12-31');
//View::dump($lastDay);
$first = new DateTime('2015-01-01');
//View::dump($first);

$date = '2014-12-31';
$date2 = '2015-01-01';

$weekStartDate = date('W', strtotime('2021-12-31'));
echo $weekStartDate . PHP_EOL;
$weekStartDate = date('W', strtotime('2022-01-01'));
echo $weekStartDate . PHP_EOL;

$firstday = date('Y-m-d', strtotime("this week", strtotime($date)));
$firstday1 = date('Y-m-d', strtotime("this week", strtotime($date2)));

echo $firstday . PHP_EOL;
echo $firstday1 . PHP_EOL;

