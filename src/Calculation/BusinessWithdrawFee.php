<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Model\TransactionInterface;

class BusinessWithdrawFee extends AbstractCommissionFee
{
    public const OPERATION_TYPE_BUSINESS_WITHDRAW_RATE = 0.5;

    /**
     * @param TransactionInterface $transaction
     * @return float
     */
    public function calculate(TransactionInterface $transaction): float
    {
        return $this->getAmountInEur($transaction) * self::OPERATION_TYPE_BUSINESS_WITHDRAW_RATE * .01;
    }
}