<?php

namespace Shojibflamon\PayseraAssignment\Model;

class CurrencyPrecision implements CurrencyInterface
{
    /**
     * @var string
     */
    private string $code;

    /**
     * @var string
     */
    private string $precision;


    /**
     * @param $code
     * @param $precision
     */
    public function __construct($code, $precision)
    {
        $this->code = strtoupper($code);
        $this->precision = $precision;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getPrecision(): string
    {
        return $this->precision;
    }
}