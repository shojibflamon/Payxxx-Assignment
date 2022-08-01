<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\TransactionInterface;

class PrivateWithdrawFee extends AbstractCommissionFee
{
    use Dump;

    public const OPERATION_TYPE_PRIVATE_WITHDRAW_RATE = 0.3;

    public const WITHDRAW_LIMIT_IN_WEEK = 3;

    public const CREDIT_LIMIT_IN_WEEK = 1000;

    /**
     * @var int
     */
    private int $withdrawLimit;

    /**
     * @var float|int
     */
    private float $creditLimit;

    /**
     * @var array
     */
    private array $arrayDb;

    public function __construct()
    {
        $this->withdrawLimit = self::WITHDRAW_LIMIT_IN_WEEK;
        $this->creditLimit = self::CREDIT_LIMIT_IN_WEEK;
        $this->arrayDb = [];
    }

    /**
     * @param TransactionInterface $transaction
     * @return float
     */
    public function calculate(TransactionInterface $transaction): float
    {
        $this->resetCredentials();

        $operationAmount = $transaction->getAmount()->getAmount();
        $operationCurrency = $transaction->getAmount()->getOperationCurrency();
        $baseCurrency = $transaction->getAmount()->getBaseCurrency();

        $amountInEuro = $transaction->getAmount()->getCurrencyConverter()->convert($operationAmount, $operationCurrency, $baseCurrency);

        $weekStartDate = $transaction->getDateOperation()->getFirstDayOfWeek();

        $userId = $transaction->getUser()->getUserId();

        if (array_key_exists($userId, $this->arrayDb) && array_key_exists($weekStartDate, $this->arrayDb[$userId])) {
            $lastTransaction = $this->arrayDb[$userId][$weekStartDate];
            $this->creditLimit = $lastTransaction['creditLimit'];
            $this->withdrawLimit = $lastTransaction['withdrawLimit'];
        }

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

        if ($transaction->getAmount()->getOperationCurrency()->getCode() !== 'EUR') {
            $commissionableAmount = $transaction->getAmount()->getCurrencyConverter()->convert($commissionableAmount, $baseCurrency, $operationCurrency);
        }

        return $commissionableAmount * self::OPERATION_TYPE_PRIVATE_WITHDRAW_RATE * .01;
    }

    /**
     * @return void
     */
    private function resetCredentials(): void
    {
        $this->withdrawLimit = self::WITHDRAW_LIMIT_IN_WEEK;
        $this->creditLimit = self::CREDIT_LIMIT_IN_WEEK;
    }

    /**
     * @return void
     */
    private function decreaseWithdrawLimit(): void
    {
        if ($this->withdrawLimit > 0) {
            $this->withdrawLimit--;
        }
    }
}