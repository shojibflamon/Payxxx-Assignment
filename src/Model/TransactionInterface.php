<?php

namespace Shojibflamon\PayseraAssignment\Model;

interface TransactionInterface
{
    /**
     * @return mixed
     */
    public function getDateOperation();

    /**
     * @return mixed
     */
    public function getUser();

    /**
     * @return mixed
     */
    public function getOperationType();

    /**
     * @return mixed
     */
    public function getAmount();
}