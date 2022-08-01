<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\TransactionInterface;

class BusinessWithdrawFee extends AbstractCommissionFee
{
    use Dump;

    public const OPERATION_TYPE_BUSINESS_WITHDRAW_RATE = 0.5;

    /**
     * @param TransactionInterface $transaction
     * @return float
     */
    public function calculate(TransactionInterface $transaction): float
    {
        $operationAmount = $transaction->getAmount()->getAmount();
        $operationCurrency = $transaction->getAmount()->getOperationCurrency();
        $baseCurrency = $transaction->getAmount()->getBaseCurrency();

        $amountInEuro = $transaction->getAmount()->getCurrencyConverter()->convert($operationAmount, $operationCurrency, $baseCurrency);

        return $amountInEuro * self::OPERATION_TYPE_BUSINESS_WITHDRAW_RATE * .01;
    }
}