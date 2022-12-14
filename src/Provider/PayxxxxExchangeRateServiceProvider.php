<?php

namespace Shojibflamon\PayxxxxAssignment\Provider;

use Shojibflamon\PayxxxxAssignment\Client\ClientInterface;
use Shojibflamon\PayxxxxAssignment\Client\CurlClient;
use Shojibflamon\PayxxxxAssignment\Model\CurrencyInterface;

class PayxxxxExchangeRateServiceProvider implements ExchangeRateServiceProviderInterface
{
    public const PAYXXXX_EXCHAGNE_RATE_API = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

    /**
     * @var CurlClient|ClientInterface
     */

    private CurlClient $client;
    /**
     * @var string|false
     */

    private string $date;
    /**
     * @var string
     */

    private string $exchangeRateSource;

    /**
     * @param ClientInterface|NULL $client
     */
    public function __construct(ClientInterface $client = NULL)
    {
        $this->client = $client ?? new CurlClient();
        $this->date = date('Y-m-d');
        $this->exchangeRateSource = 'static';
    }

    /**
     * @param mixed $exchangeRateSource
     */
    public function setExchangeRateSource($exchangeRateSource): PayxxxxExchangeRateServiceProvider
    {
        $this->exchangeRateSource = $exchangeRateSource;
        return $this;
    }

    /**
     * @param CurrencyInterface $sourceCurrency
     * @param CurrencyInterface $targetCurrency
     * @return ExchangeRateServiceResponse
     */
    public function getExchangeRate(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): ExchangeRateServiceResponse
    {
        // SAME CURRENCY
        if ($sourceCurrency->getCode() === $targetCurrency->getCode()) {
            return new ExchangeRateServiceResponse($sourceCurrency, $targetCurrency, 1);
        }

        // INVERSE CURRENCY
        if ('EUR' === $targetCurrency->getCode()) {
            $revertExchangeRate = $this->getExchangeRate($targetCurrency, $sourceCurrency);
            return new ExchangeRateServiceResponse($sourceCurrency, $targetCurrency, 1 / $revertExchangeRate->getRatio());
        }

        // EXCHANGE RATE SOURCE
        if ($this->exchangeRateSource === 'live') {
            $exchangeRateResponse = $this->client->setUrl(self::PAYXXXX_EXCHAGNE_RATE_API)->setMethod('GET')->callApi()->getResponseDecode(true);
        } elseif ($this->exchangeRateSource === 'static') {
            $exchangeRateResponse = $this->getExchangeRateStatic();
        } else {
            throw new \RuntimeException("Unexpected ExchangeRate Source $this->exchangeRateSource");
        }

        if ($exchangeRateResponse['date'] === $this->date && isset($exchangeRateResponse['rates'][$targetCurrency->getCode()])) {
            return new ExchangeRateServiceResponse($sourceCurrency, $targetCurrency, $exchangeRateResponse['rates'][$targetCurrency->getCode()]);
        }

        throw new \RuntimeException(sprintf("No exchange rate registered for converting %s to %s", $sourceCurrency->getCode(), $targetCurrency->getCode()));

    }

    /**
     * @return array
     */
    public function getExchangeRateStatic(): array
    {
        return [
            'base' => 'EUR',
            'date' => $this->date,
            'rates' =>
                [
                    'USD' => 1.1497,
                    'JPY' => 129.53,
                    'EUR' => 1,
                ]
        ];
    }
}