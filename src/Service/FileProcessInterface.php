<?php

namespace Shojibflamon\PayxxxxAssignment\Service;

interface FileProcessInterface
{
    /**
     * @return TransactionFactory
     */
    public function transformation(): TransactionFactory;
}