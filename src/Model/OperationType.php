<?php

namespace Shojibflamon\PayseraAssignment\Model;

class OperationType
{

    public const OPERATION_TYPE_WITHDRAW = 'withdraw';

    public const OPERATION_TYPE_DEPOSIT = 'deposit';

    /**
     * @var
     */
    private $operationType;

    /**
     * @param $operationType
     */
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