<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Model\TransactionInterface;

class DepositFee extends AbstractCommissionFee
{
    public const OPERATION_TYPE_DEPOSIT_RATE = 0.03;

    /**
     * @param TransactionInterface $transaction
     * @return float
     */
    public function calculate(TransactionInterface $transaction): float
    {
        return $this->getAmountInEur($transaction) * self::OPERATION_TYPE_DEPOSIT_RATE * .01;
    }
}