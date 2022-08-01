<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Model\TransactionInterface;

interface TransactionFactoryInterface
{
    /**
     * @return array
     */
    public function getTransactions(): array;

    /**
     * @param array $transactions
     * @return void
     */
    public function setTransactions(array $transactions): void;

    /**
     * @param TransactionInterface $transaction
     * @return void
     */
    public function pushTransaction(TransactionInterface $transaction): void;
}