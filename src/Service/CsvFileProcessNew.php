<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Invalid;
use Shojibflamon\PayseraAssignment\Calculation\Transaction;
use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;

class CsvFileProcessNew implements FileProcessInterface
{

    use Dump;

    /**
     * @var array
     */
    public array $data;

    /**
     * @var array
     */
    private array $transactions;
    public $fileData;



    /**
     * @param $file
     */
    public function __construct(FileData $fileData)
    {
        $this->fileData = $fileData;
//        $this->data = [];
        $this->transactions = [];
        $this->processFile();
    }


    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }


    /**
     * @return array
     */
    public function processFile(): array
    {
        return $this->parseStringFromCsv();
    }

    /**
     * @return array
     */
    public function parseStringFromCsv(): array
    {
        return $this->transactions = array_map('str_getcsv', $this->fileData->getData()[0]);
    }

    /**
     * @return $this
     */
    public function convertObject(): CsvFileProcessNew
    {
        foreach ($this->transactions as $key => $transaction) {

//            if ($key != 0){
//                continue;
//            }

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
     * @param $date
     * @return DateOperation
     */
    public function bindDate($date): DateOperation
    {
        return new DateOperation($date);
    }



}