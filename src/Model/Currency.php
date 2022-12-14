<?php

namespace Shojibflamon\PayxxxxAssignment\Model;

class Currency implements CurrencyInterface
{
    /**
     * @var string
     */
    private string $code;

    /**
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = strtoupper($code);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}