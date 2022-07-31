<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

interface CommissionFeeInterface
{
    public function calculate(Transaction $transaction);
}