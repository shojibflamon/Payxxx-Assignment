<?php

namespace Shojibflamon\PayseraAssignment\Provider;

use Shojibflamon\PayseraAssignment\Model\Currency;

Class PaymentServiceResponse
{

    private Currency $sourceCurrency;
    private Currency $targetCurrency;
    private float $ratio;

    public function __construct(Currency $sourceCurrency, Currency $targetCurrency, float $ratio)
    {
        $this->sourceCurrency = $sourceCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->ratio = $ratio;
    }

    /**
     * @return mixed
     */
    public function getSourceCurrency()
    {
        return $this->sourceCurrency;
    }

    /**
     * @return mixed
     */
    public function getTargetCurrency()
    {
        return $this->targetCurrency;
    }

    /**
     * @return mixed
     */
    public function getRatio()
    {
        return $this->ratio;
    }


}