<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Model\Currency;
use Shojibflamon\PayseraAssignment\Provider\PaymentServiceResponse;

class CurrencyConverter
{
    private $exchangeRates = [];

    public function __construct(PaymentServiceResponse ...$exchangeRates)
    {
        foreach ($exchangeRates as $exchangeRate) {
            $this->registerExchangeRate($exchangeRate);
        }
    }

    private function registerExchangeRate(PaymentServiceResponse $exchangeRate): void
    {
        $source = $exchangeRate->getSourceCurrency()->getCode();
        $target = $exchangeRate->getTargetCurrency()->getCode();
        $this->exchangeRates[$source][$target] = $exchangeRate->getRatio();

        if (!$this->hasExchangeRate($exchangeRate->getTargetCurrency()->getCode(), $exchangeRate->getSourceCurrency()->getCode())) {
            $source = $exchangeRate->getTargetCurrency()->getCode();
            $target = $exchangeRate->getSourceCurrency()->getCode();
            $this->exchangeRates[$source][$target] = 1 / $exchangeRate->getRatio();
        }
    }

    private function getExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode): ?float
    {
        return $this->exchangeRates[$sourceCurrencyCode][$targetCurrencyCode] ?? NULL;
    }

    private function hasExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode): bool
    {
        return isset($this->exchangeRates[$sourceCurrencyCode][$targetCurrencyCode]);
    }


    public function convert(float $amount, Currency $sourceCurrency, Currency $targetCurrency): float
    {
        $ratio = $this->getExchangeRate($sourceCurrency->getCode(), $targetCurrency->getCode());

        if (NULL === $ratio) {
            throw new ExchangeRateNotFoundException($sourceCurrency, $targetCurrency, sprintf("No exchange rate registered for converting %s to %s", $sourceCurrency->getCode(), $targetCurrency->getCode()));
        }

        return $amount * $ratio;
    }
}
