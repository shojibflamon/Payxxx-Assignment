<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

use Shojibflamon\PayseraAssignment\Model\Amount;
use Shojibflamon\PayseraAssignment\Model\DateOperation;
use Shojibflamon\PayseraAssignment\Model\OperationType;
use Shojibflamon\PayseraAssignment\Model\User;

class Transaction implements TransactionInterface
{
    private DateOperation $dateOperation;
    private User $user;
    private OperationType $operationType;
    private Amount $amount;

    public function __construct(
        DateOperation $dateOperation,
        User          $user,
        OperationType $operationType,
        Amount        $amount
    )
    {
        $this->dateOperation = $dateOperation;
        $this->user = $user;
        $this->operationType = $operationType;
        $this->amount = $amount;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

}