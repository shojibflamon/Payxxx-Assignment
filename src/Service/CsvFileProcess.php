<?php

namespace Shojibflamon\PayxxxxAssignment\Service;

use Shojibflamon\PayxxxxAssignment\Model\Transaction;
use Shojibflamon\PayxxxxAssignment\Model\Amount;
use Shojibflamon\PayxxxxAssignment\Model\DateOperation;
use Shojibflamon\PayxxxxAssignment\Model\OperationType;
use Shojibflamon\PayxxxxAssignment\Model\User;

class CsvFileProcess implements FileProcessInterface
{
    /**
     * @var array
     */
    private array $transactions;

    /**
     * @var FileData
     */
    private FileData $fileData;


    /**
     * @param FileData $fileData
     */
    public function __construct(FileData $fileData)
    {
        $this->fileData = $fileData;
        $this->transactions = [];
    }

    /**
     * @return CsvFileProcess
     */
    public function parseStringFromCsv(): CsvFileProcess
    {
        $this->transactions = array_map('str_getcsv', $this->fileData->getData()[0]);
        return $this;
    }

    /**
     * @return TransactionFactory
     */
    public function transformation(): TransactionFactory
    {
        $transactionFactory = new TransactionFactory();
        foreach ($this->transactions as $single) {

            $date = $this->bindDate($single[0]);
            $user = $this->bindUser($single[1], $single[2]);
            $operationType = $this->bindOperationType($single[3]);
            $amount = $this->bindAmount($single[4], $single[5]);

            $transactionFactory->pushTransaction(new Transaction($date, $user, $operationType, $amount));
        }

        return $transactionFactory;
    }

    /**
     * @param $date
     * @return DateOperation
     */
    public function bindDate($date): DateOperation
    {
        return new DateOperation($date);
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
}