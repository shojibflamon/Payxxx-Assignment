<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Model\Currency;
use Shojibflamon\PayxxxxAssignment\Model\TransactionInterface;

abstract class AbstractCommissionFee
{
    protected Currency $operationCurrency;
    protected Currency $baseCurrency;

    /**
     * @param TransactionInterface $transaction
     * @return mixed
     */
    abstract public function calculate(TransactionInterface $transaction);

    protected function getAmountInEur(TransactionInterface $transaction)
    {
        $operationAmount = $transaction->getAmount()->getAmount();
        $this->operationCurrency = $transaction->getAmount()->getOperationCurrency();

        $this->baseCurrency = $transaction->getAmount()->getBaseCurrency();

        return $transaction->getAmount()->getCurrencyConverter()->convert($operationAmount, $this->operationCurrency, $this->baseCurrency);
    }
}