<?php

namespace Shojibflamon\PayseraAssignment\Calculation;
use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\Currency;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;
use Shojibflamon\PayseraAssignment\Model\UserType;

class Transaction
{
    private DateOperation $dateOperation;
    private User $user;
    private UserType $userType;
    private OperationType $operationType;
    private Amount $amount;
    private Currency $currency;


    public function __construct(
        DateOperation $dateOperation,
        User $user,
        UserType $userType,
        OperationType $operationType,
        Amount $amount,
        Currency $currency
    )
    {
        $this->dateOperation = $dateOperation;
        $this->user = $user;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return DateOperation
     */
    public function getDateOperation(): DateOperation
    {
        return $this->dateOperation;
    }

    /**
     * @return OperationType
     */
    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return UserType
     */
    public function getUserType(): UserType
    {
        return $this->userType;
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }






}