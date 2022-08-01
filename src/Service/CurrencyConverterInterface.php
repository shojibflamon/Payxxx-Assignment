<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Model\CurrencyInterface;

interface CurrencyConverterInterface
{
    /**
     * @param float $amount
     * @param CurrencyInterface $sourceCurrency
     * @param CurrencyInterface $targetCurrency
     * @return float
     */
    public function convert(float $amount, CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): float;
}