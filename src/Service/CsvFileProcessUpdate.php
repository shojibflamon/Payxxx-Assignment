<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Calculation\TransactionInterface;

class CsvFileProcessUpdate implements FileProcessInterface
{
    private array $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param array $transactions
     */
    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }

    /**
     * @param array $transactions
     */
    public function pushTransaction(TransactionInterface $transaction): void
    {
        $this->transactions[] = $transaction;
    }


    public function processFile(): array
    {
        // TODO: Implement processFile() method.
    }
}