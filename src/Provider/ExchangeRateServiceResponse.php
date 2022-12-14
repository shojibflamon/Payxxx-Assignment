<?php

namespace Shojibflamon\PayxxxxAssignment\Provider;

use Shojibflamon\PayxxxxAssignment\Model\CurrencyInterface;

class ExchangeRateServiceResponse implements ExchangeRateServiceResponseInterface
{

    /**
     * @var CurrencyInterface
     */
    private CurrencyInterface $sourceCurrency;

    /**
     * @var CurrencyInterface
     */
    private CurrencyInterface $targetCurrency;

    /**
     * @var float
     */
    private float $ratio;

    /**
     * @param CurrencyInterface $sourceCurrency
     * @param CurrencyInterface $targetCurrency
     * @param float $ratio
     */
    public function __construct(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency, float $ratio)
    {
        $this->sourceCurrency = $sourceCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->ratio = $ratio;
    }

    /**
     * @return CurrencyInterface
     */
    public function getSourceCurrency(): CurrencyInterface
    {
        return $this->sourceCurrency;
    }

    /**
     * @return CurrencyInterface
     */
    public function getTargetCurrency(): CurrencyInterface
    {
        return $this->targetCurrency;
    }

    /**
     * @return float
     */
    public function getRatio(): float
    {
        return $this->ratio;
    }
}