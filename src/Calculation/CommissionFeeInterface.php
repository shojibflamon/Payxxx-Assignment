<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

interface CommissionFeeInterface
{
    public function calculate(TransactionInterface $transaction);
}