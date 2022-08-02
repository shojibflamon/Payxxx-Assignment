<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Helper\Dump;
use Shojibflamon\PayxxxxAssignment\Model\TransactionInterface;

class DepositFee extends AbstractCommissionFee
{
    use Dump;

    public const OPERATION_TYPE_DEPOSIT_RATE = 0.03;

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

        return $amountInEuro * self::OPERATION_TYPE_DEPOSIT_RATE * .01;
    }
}