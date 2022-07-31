<?php

namespace Shojibflamon\PayseraAssignment\Calculation;

interface TransactionInterface
{
    public function getDateOperation();

    public function getUser();

    public function getOperationType();

    public function getAmount();
}