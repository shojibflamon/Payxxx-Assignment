<?php

namespace Shojibflamon\PayseraAssignment\Model;

interface CurrencyInterface
{
    /**
     * @return string
     */
    public function getCode(): string;
}