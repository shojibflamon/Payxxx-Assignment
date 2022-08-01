<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Model\TransactionInterface;

abstract class AbstractCommissionFee
{
    /**
     * @param TransactionInterface $transaction
     * @return mixed
     */
    abstract public function calculate(TransactionInterface $transaction);

}