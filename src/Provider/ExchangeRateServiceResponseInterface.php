<?php

namespace Shojibflamon\PayseraAssignment\Provider;

interface ExchangeRateServiceResponseInterface
{
    public function getSourceCurrency();

    public function getTargetCurrency();

    public function getRatio();
}