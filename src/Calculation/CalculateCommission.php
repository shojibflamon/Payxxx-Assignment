<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Helper\Dump;
use Shojibflamon\PayxxxxAssignment\Model\OperationType;
use Shojibflamon\PayxxxxAssignment\Model\User;
use Shojibflamon\PayxxxxAssignment\Service\TransactionFactoryInterface;

class CalculateCommission implements CalculateCommissionInterface
{
    use Dump;

    /**
     * @var array
     */
    private array $commissionFees;

    /**
     * @var array
     */
    private array $transactions;

    /**
     * @var float
     */
    private $commissionFee;

    /**
     * @var DepositFee
     */
    private DepositFee $depositFee;

    /**
     * @var BusinessWithdrawFee
     */
    private BusinessWithdrawFee $businessWithdrawFee;

    /**
     * @var PrivateWithdrawFee
     */
    private PrivateWithdrawFee $privateWithdrawFee;

    /**
     * @var int
     */
    private int $precision;


    /**
     * @param TransactionFactoryInterface $fileProcess
     */
    public function __construct(TransactionFactoryInterface $fileProcess)
    {
        $this->commissionFee = 0;
        $this->precision = 2;

        $this->commissionFees = [];
        $this->transactions = $fileProcess->getTransactions();
        $this->depositFee = new DepositFee();
        $this->businessWithdrawFee = new BusinessWithdrawFee();
        $this->privateWithdrawFee = new PrivateWithdrawFee();
    }

    /**
     * @return array
     */
    public function process(): array
    {
        foreach ($this->transactions as $key => $transaction) {

            /*if ($key != 0) {
                continue;
            }
            */
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

    /**
     * @param $transaction
     * @return bool
     */
    private function isDepositAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_DEPOSIT;
    }

    /**
     * @param $transaction
     * @return bool
     */
    private function isBusinessWithdrawAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_WITHDRAW &&
            $transaction->getUser()->getUserType() === User::USER_TYPE_BUSINESS;
    }

    /**
     * @param $transaction
     * @return bool
     */
    private function isPrivateWithdrawAction($transaction): bool
    {
        return $transaction->getOperationType()->getOperationType() === OperationType::OPERATION_TYPE_WITHDRAW &&
            $transaction->getUser()->getUserType() === User::USER_TYPE_PRIVATE;
    }

}