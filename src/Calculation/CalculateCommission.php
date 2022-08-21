<?php

namespace Shojibflamon\PayxxxxAssignment\Calculation;

use Shojibflamon\PayxxxxAssignment\Model\OperationType;
use Shojibflamon\PayxxxxAssignment\Model\User;
use Shojibflamon\PayxxxxAssignment\Service\TransactionFactoryInterface;

class CalculateCommission implements CalculateCommissionInterface
{

    public CONST PRECISION = 2;

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
     * @param TransactionFactoryInterface $transactionFactory
     */
    public function __construct(TransactionFactoryInterface $transactionFactory)
    {
        $this->commissionFee = 0;

        $this->commissionFees = [];
        $this->transactions = $transactionFactory->getTransactions();
        $this->depositFee = new DepositFee();
        $this->businessWithdrawFee = new BusinessWithdrawFee();
        $this->privateWithdrawFee = new PrivateWithdrawFee();
    }

    /**
     * @return array
     */
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

            $precision = self::PRECISION;

            if ($transaction->getAmount()->getOperationCurrency()->getCode() === 'JPY') {
                $precision = 0;
            }

            $this->commissionFee = $transaction->getAmount()->ceiling($this->commissionFee, $precision);

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