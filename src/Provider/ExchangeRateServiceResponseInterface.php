<?php

namespace Shojibflamon\PayxxxxAssignment\Provider;

interface ExchangeRateServiceResponseInterface
{
    /**
     * @return mixed
     */
    public function getSourceCurrency();

    /**
     * @return mixed
     */
    public function getTargetCurrency();

    /**
     * @return mixed
     */
    public function getRatio();
}