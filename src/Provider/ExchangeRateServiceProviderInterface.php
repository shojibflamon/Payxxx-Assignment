<?php

namespace Shojibflamon\PayseraAssignment\Provider;

use Shojibflamon\PayseraAssignment\Model\CurrencyInterface;

interface ExchangeRateServiceProviderInterface
{
    /**
     * @param CurrencyInterface $sourceCurrency
     * @param CurrencyInterface $targetCurrency
     * @return ExchangeRateServiceResponse
     */
    public function getExchangeRate(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): ExchangeRateServiceResponse;
}
