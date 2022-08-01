<?php

namespace Shojibflamon\PayseraAssignment\Service;

use Shojibflamon\PayseraAssignment\Model\CurrencyInterface;
use Shojibflamon\PayseraAssignment\Provider\ExchangeRateServiceResponse;

class CurrencyConverter implements CurrencyConverterInterface
{
    /**
     * @var array
     */
    private array $exchangeRates = [];

    /**
     * @param ExchangeRateServiceResponse ...$exchangeRates
     */
    public function __construct(ExchangeRateServiceResponse ...$exchangeRates)
    {
        foreach ($exchangeRates as $exchangeRate) {
            $this->registerExchangeRate($exchangeRate);
        }
    }

    /**
     * @param ExchangeRateServiceResponse $exchangeRate
     * @return void
     */
    private function registerExchangeRate(ExchangeRateServiceResponse $exchangeRate): void
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

    /**
     * @param string $sourceCurrencyCode
     * @param string $targetCurrencyCode
     * @return float|null
     */
    private function getExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode): ?float
    {
        return $this->exchangeRates[$sourceCurrencyCode][$targetCurrencyCode] ?? NULL;
    }

    /**
     * @param string $sourceCurrencyCode
     * @param string $targetCurrencyCode
     * @return bool
     */
    private function hasExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode): bool
    {
        return isset($this->exchangeRates[$sourceCurrencyCode][$targetCurrencyCode]);
    }

    /**
     * @param float $amount
     * @param CurrencyInterface $sourceCurrency
     * @param CurrencyInterface $targetCurrency
     * @return float
     */
    public function convert(float $amount, CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): float
    {
        $ratio = $this->getExchangeRate($sourceCurrency->getCode(), $targetCurrency->getCode());

        if (NULL === $ratio) {
            throw new \RuntimeException(sprintf("No exchange rate registered for converting %s to %s", $sourceCurrency->getCode(), $targetCurrency->getCode()));
        }

        return $amount * $ratio;
    }
}
