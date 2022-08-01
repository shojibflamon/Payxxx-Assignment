<?php

namespace Shojibflamon\PayseraAssignment\Service;

interface FileProcessInterface
{
    /**
     * @return TransactionFactory
     */
    public function transformation(): TransactionFactory;
}