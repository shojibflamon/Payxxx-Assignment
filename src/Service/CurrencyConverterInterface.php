<?php

namespace Shojibflamon\PayxxxxAssignment\Service;

use Shojibflamon\PayxxxxAssignment\Model\CurrencyInterface;

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