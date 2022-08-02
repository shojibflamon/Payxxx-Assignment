<?php

namespace Shojibflamon\PayxxxxAssignment\Model;

interface CurrencyInterface
{
    /**
     * @return string
     */
    public function getCode(): string;
}