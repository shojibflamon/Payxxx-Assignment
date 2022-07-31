<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Helper\Dump;

class PrivateWithdraw implements CommissionFeeInterface
{
    use Dump;

    public const OPERATION_TYPE_PRIVATE_WITHDRAW_RATE = 0.3;

    private int $withdrawLimit;
    private float $creditLimit;
    private array $arrayDb;

    public function __construct()
    {
        $this->withdrawLimit = 3;
        $this->creditLimit = 1000;
        $this->arrayDb = [];
    }

    public function calculate(Transaction $transaction ) :float
    {
        $this->resetCredentials();

//        self::dd($transaction);
        $operationAmount = $transaction->getAmount()->getAmount();
        $operationCurrency = $transaction->getAmount()->getOperationCurrency();
        $baseCurrency = $transaction->getAmount()->getBaseCurrency();

        $amountInEuro = $transaction->getAmount()->getCurrencyConverter()->convert($operationAmount, $operationCurrency, $baseCurrency);

        $weekStartDate = $transaction->getDateOperation()->getFirstDayOfWeek();

        $userId = $transaction->getUser()->getUserId();

//        self::dd($this->arrayDb);

        if (array_key_exists($userId, $this->arrayDb) && array_key_exists($weekStartDate, $this->arrayDb[$userId])) {
            $lastTransaction = $this->arrayDb[$userId][$weekStartDate];
            $this->creditLimit = $lastTransaction['creditLimit'];
            $this->withdrawLimit = $lastTransaction['withdrawLimit'];
        }
        self::dd($this->creditLimit);
        if ($amountInEuro < $this->creditLimit) {
            $exceededAmount = $commissionableAmount = 0;
            $this->creditLimit -= $amountInEuro;
        } else {
            $commissionableAmount = $exceededAmount = $amountInEuro - $this->creditLimit;
            $this->creditLimit = 0;
        }

        $this->decreaseWithdrawLimit();

        $this->arrayDb[$userId][$weekStartDate] = [
            'exceededAmount' => $exceededAmount,
            'commissionableAmount' => $commissionableAmount,
            'creditLimit' => $this->creditLimit,
            'withdrawLimit' => $this->withdrawLimit,
        ];
//self::dd($this->arrayDb[$userId]);

        if ($transaction->getCurrency()->getCode() !== 'EUR') {
            $commissionableAmount = $transaction->getAmount()->getCurrencyConverter()->convert($commissionableAmount, $baseCurrency, $operationCurrency);
        }

        return $commissionableAmount * self::OPERATION_TYPE_PRIVATE_WITHDRAW_RATE * .01;
    }

    private function resetCredentials(): void
    {
        $this->commissionFee = 0;
        $this->withdrawLimit = 3;
        $this->creditLimit = 1000;
    }

    private function decreaseWithdrawLimit(): void
    {
        if ($this->withdrawLimit > 0) {
            $this->withdrawLimit--;
        }
    }
}