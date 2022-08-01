<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Model\TransactionInterface;

interface CommissionFeeInterface
{
    /**
     * @param TransactionInterface $transaction
     * @return mixed
     */
    public function calculate(TransactionInterface $transaction);
}