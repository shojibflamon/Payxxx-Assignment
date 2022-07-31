<?php

namespace Shojibflamon\PayseraAssignment\Provider;

use Shojibflamon\PayseraAssignment\Model\CurrencyInterface;

Class ExchangeRateServiceResponse implements ExchangeRateServiceResponseInterface
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