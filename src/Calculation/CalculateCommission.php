<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Helper\Dump;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;
use Shojibflamon\PayseraAssignment\Service\FileProcessInterface;

class CalculateCommission implements CalculateCommissionInterface
{
    use Dump;

    private array $commissionFees;
    private array $transactions;
    private $commissionFee;

    private DepositFee $depositFee;
    private BusinessWithdrawFee $businessWithdrawFee;
    private PrivateWithdrawFee $privateWithdrawFee;
    private int $precision;


    public function __construct(FileProcessInterface $fileProcess)
    {
        $this->commissionFee = 0;
        $this->precision = 2;

        $this->commissionFees = [];
        $this->transactions = $fileProcess->transactions;
        $this->depositFee = new DepositFee();
        $this->businessWithdrawFee = new BusinessWithdrawFee();
        $this->privateWithdrawFee = new PrivateWithdrawFee();

    }

    public function process(): array
    {
        foreach ($this->transactions as $transaction) {

            if ($this->isDepositAction($transaction)) {
                $this->commissionFee = $this->depositFee->calculate($transaction);
            }

            if ($this->isBusinessWithdrawAction($transaction)) {
                $this->commissionFee = $this->businessWithdrawFee->calculate($transaction);
            }

            if ($this->isPrivateWithdrawAction($transaction)) {
                $this->commissionFee = $this->privateWithdrawFee->calculate($transaction);
            }

            $this->precision = 2;

            if ($transaction->getAmount()->getOperationCurrency()->getCode() === 'JPY') {
                $this->precision = 0;
            }

            $this->commissionFee = $transaction->getAmount()->ceiling($this->commissionFee, $this->precision);

            $this->commissionFees[] = $this->commissionFee;

        }

        return $this->commissionFees;
    }

    private function isDepositAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_DEPOSIT;
    }

    private function isBusinessWithdrawAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_WITHDRAW &&
            $transaction->getUser()->getUserType() === User::USER_TYPE_BUSINESS;
    }

    private function isPrivateWithdrawAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_WITHDRAW &&
            $transaction->getUser()->getUserType() === User::USER_TYPE_PRIVATE;
    }

}