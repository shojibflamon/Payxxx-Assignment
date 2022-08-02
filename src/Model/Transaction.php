<?php

namespace Shojibflamon\PayxxxxAssignment\Model;

class Transaction implements TransactionInterface
{
    /**
     * @var DateOperation
     */
    private DateOperation $dateOperation;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var OperationType
     */
    private OperationType $operationType;

    /**
     * @var Amount
     */
    private Amount $amount;

    /**
     * @param DateOperation $dateOperation
     * @param User $user
     * @param OperationType $operationType
     * @param Amount $amount
     */
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