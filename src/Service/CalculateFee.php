<?php

namespace Shojibflamon\PayseraAssignment\Service;

class CalculateFee
{
    private float $commissionFee;
    private int $creditLimit;
    private Transaction $transaction;

    const OPERATION_TYPE_DEPOSIT = 'deposit';
    const OPERATION_TYPE_WITHDRAW = 'withdraw';
    const USER_TYPE_PRIVATE =  'private';
    const USER_TYPE_BUSINESS = 'business';
    const OPERATION_TYPE_DEPOSIT_RATE = 0.03;
    const OPERATION_TYPE_BUSINESS_WITHDRAW_RATE = 0.5;
    const OPERATION_TYPE_PRIVATE_WITHDRAW_RATE = 0.3;
    const CREDIT_LIMIT_IN_WEEK = 1000;
    const WITHDRAW_LIMIT_IN_WEEK = 3;

    public function __construct()
    {
        $this->commissionFee = 0;
        $this->creditLimit = 1000;
        $this->transaction = New Transaction();

    }

    
    public function calculate($rows)
    {
        foreach ($rows as $row) {

            list($operationDate, $userId, $userType, $operationType, $operationAmount, $operationCurrency) = $row;

            if ($operationType === self::OPERATION_TYPE_DEPOSIT) {

                $commissionFee = $operationAmount * OPERATION_TYPE_DEPOSIT_RATE * .01;
            }

            if ($operationType === self::OPERATION_TYPE_WITHDRAW && $userType === self::USER_TYPE_BUSINESS) {
                $commissionFee = $operationAmount * self::OPERATION_TYPE_BUSINESS_WITHDRAW_RATE * .01;
            }

//            $this->transaction->initialize($row);


        }
    }


}