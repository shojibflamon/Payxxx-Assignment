<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Calculation\Transaction;
use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\Currency;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\UserType;
use Shojibflamon\PayseraAssignment\Model\User;
use Shojibflamon\PayseraAssignment\Provider\PayseraExchangeRateServiceProvider;

class CsvFileProcess implements FileProcessInterface
{
    public string $file;
    public array $data;
    public array $transactions;

    public function __construct($file)
    {
        $this->file = $file;
        $this->data = [];
        $this->transactions = [];
        $this->processFile();
    }

    public function processFile(): array
    {
        $this->data = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $this->parseStringFromCsv();
    }

    public function parseStringFromCsv(): array
    {
        return $this->transactions = array_map('str_getcsv', $this->data);
    }

    public function convertObject()
    {
        foreach ($this->transactions as $key => $transaction) {

            $date = $this->buildDate($transaction[0]);
            $user = $this->buildUser($transaction[1],$transaction[2]);
            $userType = $this->buildUserType($transaction[2]);
            $operationType = $this->buildOperationType($transaction[3]);
            $currency = $this->buildCurrency($transaction[5]);
            $amount = $this->buildAmount($transaction[4],$transaction[5]);

            $this->transactions[$key] = new Transaction($date, $user, $userType, $operationType, $amount, $currency);
        }
        return $this;
    }

    public function buildOperationType($operationType)
    {
        return new OperationType($operationType);
    }

    public function buildAmount($amount, $operationCurrency)
    {
//        $baseCurrency = new Currency('EUR');
//        $payseraServiceProvider = new PayseraExchangeRateServiceProvider();
//        $exchangeRate = $payseraServiceProvider->setExchangeRateSource('static')->getExchangeRate($operationCurrency, $baseCurrency);
//        $currencyConverter = new CurrencyConverter($exchangeRate);
//        $amountInEuro = $currencyConverter->convert($amount, $operationCurrency, $baseCurrency);
        return new Amount($amount, $operationCurrency);
    }

    public function _buildAmount($amount, Currency $operationCurrency)
    {
        $baseCurrency = new Currency('EUR');
        $payseraServiceProvider = new PayseraExchangeRateServiceProvider();
        $exchangeRate = $payseraServiceProvider->setExchangeRateSource('static')->getExchangeRate($operationCurrency, $baseCurrency);
        $currencyConverter = new CurrencyConverter($exchangeRate);
        $amountInEuro = $currencyConverter->convert($amount, $operationCurrency, $baseCurrency);
        return new Amount($amountInEuro, $currencyConverter);
    }

    public function buildCurrency($currency)
    {
        return new Currency($currency);
    }

    public function buildUserType($userType)
    {
        return new UserType($userType);
    }

    public function buildUser($user,$userType)
    {
        return new User($user,$userType);
    }

    public function buildDate($user)
    {
        return new DateOperation($user);
    }

}