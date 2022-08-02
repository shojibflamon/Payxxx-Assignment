<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Model\TransactionInterface;

abstract class AbstractCommissionFee
{
    /**
     * @param TransactionInterface $transaction
     * @return mixed
     */
    abstract public function calculate(TransactionInterface $transaction);

}