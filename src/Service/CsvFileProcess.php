<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Calculation\Transaction;
use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;

class CsvFileProcess implements FileProcessInterface
{
    /**
     * @var string
     */
    public string $file;

    /**
     * @var array
     */
    public array $data;

    /**
     * @var array
     */
    public array $transactions;

    /**
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->data = [];
        $this->transactions = [];
        $this->processFile();
    }

    /**
     * @return array
     */
    public function processFile(): array
    {
        $this->data = file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return $this->parseStringFromCsv();
    }

    /**
     * @return array
     */
    public function parseStringFromCsv(): array
    {
        return $this->transactions = array_map('str_getcsv', $this->data);
    }

    /**
     * @return $this
     */
    public function convertObject(): CsvFileProcess
    {
        foreach ($this->transactions as $key => $transaction) {

            $date = $this->bindDate($transaction[0]);
            $user = $this->bindUser($transaction[1], $transaction[2]);
            $operationType = $this->bindOperationType($transaction[3]);
            $amount = $this->bindAmount($transaction[4], $transaction[5]);

            $this->transactions[$key] = new Transaction($date, $user, $operationType, $amount);
        }
        return $this;
    }

    /**
     * @param $operationType
     * @return OperationType
     */
    public function bindOperationType($operationType): OperationType
    {
        return new OperationType($operationType);
    }

    /**
     * @param $amount
     * @param $operationCurrency
     * @return Amount
     */
    public function bindAmount($amount, $operationCurrency): Amount
    {
        return new Amount($amount, $operationCurrency);
    }

    /**
     * @param $user
     * @param $userType
     * @return User
     */
    public function bindUser($user, $userType): User
    {
        return new User($user, $userType);
    }

    /**
     * @param $user
     * @return DateOperation
     */
    public function bindDate($user): DateOperation
    {
        return new DateOperation($user);
    }

}