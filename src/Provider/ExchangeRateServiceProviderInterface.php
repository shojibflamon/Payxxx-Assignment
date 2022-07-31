<?php

namespace Shojibflamon\PayseraAssignment\Provider;


use Shojibflamon\PayseraAssignment\Model\CurrencyInterface;

interface ExchangeRateServiceProviderInterface
{

    public function getExchangeRate(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency) : ExchangeRateServiceResponse;

}
