<?php

namespace Shojibflamon\PayseraAssignment\Model;

class OperationType
{
    const OPERATION_TYPE_WITHDRAW = 'withdraw';
    const OPERATION_TYPE_DEPOSIT = 'deposit';

    private $operationType;

    public function __construct($operationType)
    {
        $this->operationType = $operationType;
    }

    /**
     * @return mixed
     */
    public function getOperationType()
    {
        return $this->operationType;
    }



}