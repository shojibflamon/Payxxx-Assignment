<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Helper\Dump;

class DepositFee implements CommissionFeeInterface
{
    use Dump;
    public const OPERATION_TYPE_DEPOSIT_RATE = 0.03;

    public function calculate(TransactionInterface $transaction) :float
    {
        $operationAmount = $transaction->getAmount()->getAmount();
        $operationCurrency = $transaction->getAmount()->getOperationCurrency();
        $baseCurrency = $transaction->getAmount()->getBaseCurrency();

        $amountInEuro = $transaction->getAmount()->getCurrencyConverter()->convert($operationAmount, $operationCurrency, $baseCurrency);

        return $amountInEuro * self::OPERATION_TYPE_DEPOSIT_RATE * .01;
    }
}