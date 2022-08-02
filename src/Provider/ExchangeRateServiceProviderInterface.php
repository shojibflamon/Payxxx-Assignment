<?php

namespace Shojibflamon\PayxxxxAssignment\Provider;

use Shojibflamon\PayxxxxAssignment\Model\CurrencyInterface;

interface ExchangeRateServiceProviderInterface
{
    /**
     * @param CurrencyInterface $sourceCurrency
     * @param CurrencyInterface $targetCurrency
     * @return ExchangeRateServiceResponse
     */
    public function getExchangeRate(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): ExchangeRateServiceResponse;
}
