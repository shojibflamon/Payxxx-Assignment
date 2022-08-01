<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Model\TransactionInterface;

class TransactionFactory implements TransactionFactoryInterface
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
     * @param TransactionInterface $transaction
     */
    public function pushTransaction(TransactionInterface $transaction): void
    {
        $this->transactions[] = $transaction;
    }
}