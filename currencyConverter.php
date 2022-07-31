<?php
include_once 'start.php';


class View
{
    public static function dd($data)
    {
        self::dump($data);
        die();
    }

    public static function dump($data)
    {
        echo '<pre>', print_r($data, true), '</pre>';
    }
}

// Currency
interface CurrencyInterface
{
    public function getCode(): string;
}

final class Currency implements CurrencyInterface
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = strtoupper($code);
    }

    public function getCode(): string
    {
        return $this->code;
    }
}


interface CurrencyConverterInterface
{
    public function convert(float $amount, CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): float;
}

final class CurrencyConverter implements CurrencyConverterInterface
{
    private $exchangeRates = [];

    public function __construct(ExchangeRateInterface ...$exchangeRates)
    {
        foreach ($exchangeRates as $exchangeRate) {
            $this->registerExchangeRate($exchangeRate);
        }
    }

    private function registerExchangeRate(ExchangeRateInterface $exchangeRate): void
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


    public function convert(float $amount, CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency): float
    {
        $ratio = $this->getExchangeRate($sourceCurrency->getCode(), $targetCurrency->getCode());

        if (NULL === $ratio) {
            throw new ExchangeRateNotFoundException($sourceCurrency, $targetCurrency, sprintf("No exchange rate registered for converting %s to %s", $sourceCurrency->getCode(), $targetCurrency->getCode()));
        }

        return $amount * $ratio;
    }
}


interface ExchangeRateInterface
{
    public function getRatio(): float;

    public function getSourceCurrency(): CurrencyInterface;

    public function getTargetCurrency(): CurrencyInterface;
}

final class ExchangeRate implements ExchangeRateInterface
{
    private $sourceCurrency;
    private $targetCurrency;
    private $ratio;

    public function __construct(
        CurrencyInterface $sourceCurrency,
        CurrencyInterface $targetCurrency,
        float             $ratio
    )
    {
        $this->sourceCurrency = $sourceCurrency;
        $this->targetCurrency = $targetCurrency;
        if ($ratio <= 0) {
            throw new \InvalidArgumentException("Ratio must be a positive float.");
        }
        $this->ratio = $ratio;
    }

    public function getRatio(): float
    {
        return $this->ratio;
    }

    public function getSourceCurrency(): CurrencyInterface
    {
        return $this->sourceCurrency;
    }

    public function getTargetCurrency(): CurrencyInterface
    {
        return $this->targetCurrency;
    }

    public function swapCurrencies(): ExchangeRateInterface
    {
        $clone = clone $this;
        $clone->sourceCurrency = $this->targetCurrency;
        $clone->targetCurrency = $this->sourceCurrency;
        $clone->ratio = 1 / $this->ratio;
        return $clone;
    }
}

interface ExchangeRateFactoryInterface
{
    public function create(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency, float $ratio): ExchangeRateInterface;
}

final class NativeExchangeRateFactory implements ExchangeRateFactoryInterface
{
    public function create(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency, float $ratio): ExchangeRateInterface
    {
        return new ExchangeRate($sourceCurrency, $targetCurrency, $ratio);
    }
}


interface ExchangeRateProviderInterface
{
    public function getExchangeRate(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency, DateTimeInterface $date = NULL): ExchangeRateInterface;
}

final class EuropeanCentralBankProvider implements ExchangeRateProviderInterface
{
    const LIVE_FEED_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    const NINETYDAYS_FEED_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist-90d.xml';
    const FULL_FEED_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml';


    private $exchangeRateFactory;

    public function __construct(ExchangeRateFactoryInterface $exchangeRateFactory = NULL)
    {
        $this->exchangeRateFactory = $exchangeRateFactory ?? new NativeExchangeRateFactory();
    }

    public function getExchangeRate(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency, DateTimeInterface $date = NULL): ExchangeRateInterface
    {
        // same currencies
        if ($sourceCurrency->getCode() === $targetCurrency->getCode()) {
            return $this->exchangeRateFactory->create($sourceCurrency, $targetCurrency, 1);
        }

        // Invert currencies
        if ('EUR' === $targetCurrency->getCode()) {
            $revertExchangeRate = $this->getExchangeRate($targetCurrency, $sourceCurrency, $date);
            return $this->exchangeRateFactory->create($sourceCurrency, $targetCurrency, 1 / $revertExchangeRate->getRatio());
        }

        $currencyCache = true;

        if ($currencyCache) {
            $rates = [
                date('Y-m-d') => [
                    'USD' => 1.1497,
                    'JPY' => 129.53,
                    'BGN' => 1.9558,
                    'CZK' => 24.607,
                    'DKK' => 7.4449,
                    'GBP' => 0.84558,
                    'HUF' => 400.99,
                    'PLN' => 4.742,
                    'RON' => 4.9324,
                    'SEK' => 10.4445,
                    'CHF' => 0.9765,
                    'ISK' => 139.1,
                    'NOK' => 10.0105,
                    'HRK' => 7.5145,
                    'TRY' => 18.0705,
                    'AUD' => 1.4605,
                    'BRL' => 5.4437,
                    'CAD' => 1.3035,
                    'CNY' => 6.8451,
                    'HKD' => 7.9466,
                    'IDR' => 15185.27,
                    'ILS' => 3.4891,
                    'INR' => 80.805,
                    'KRW' => 1326.65,
                    'MXN' => 20.7845,
                    'MYR' => 4.5113,
                    'NZD' => 1.6235,
                    'PHP' => 56.16,
                    'SGD' => 1.4066,
                    'THB' => 37.18,
                    'ZAR' => 17.087
                ]
            ];
        } else {
            $url = $this->pickUrl($date);

            $response = $this->getCurrencyData($url);

            $xml = new SimpleXMLElement($response);

            $rates = [];
            foreach ($xml->Cube->Cube as $cube) {
                foreach ($cube->Cube as $rate) {
                    $currency = (string)$rate['currency'];
                    $ratio = (float)(string)$rate['rate'];
                    $rates[(string)$cube['time']][$currency] = $ratio;
                }
            }
        }


        $dateString = date('Y-m-d');

        if (isset($rates[$dateString][$targetCurrency->getCode()])) {
            return $this->exchangeRateFactory->create($sourceCurrency, $targetCurrency, $rates[$dateString][$targetCurrency->getCode()]);
        }
    }

    private function pickUrl(DateTimeInterface $date): string
    {
        if ($date instanceof DateTime) {
            $date = DateTimeImmutable::createFromMutable($date);
        }

        $today = new DateTimeImmutable('today midnight', $date->getTimezone());


        switch (true) {
            case $date->format('Y-m-d') === $today->format('Y-m-d'):
                return static::LIVE_FEED_URL;

            case $date >= new DateTimeImmutable('-90 days', $date->getTimezone()):
                return static::NINETYDAYS_FEED_URL;

            default:
                return static::FULL_FEED_URL;
        }
    }

    private function getCurrencyData($url)
    {
        $curl = curl_init();

        $headers = array(
            "Accept: application/xml",
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}


// Client Code
$eur = new Currency('EUR');
$usd = new Currency('USD');

$exchangeRateProvider = new EuropeanCentralBankProvider();

$exchangeRate = $exchangeRateProvider->getExchangeRate($eur, $usd, new DateTime('today'));
$exchangeRate1 = $exchangeRateProvider->getExchangeRate($eur, $eur, new DateTime('today'));
View::dump($exchangeRate);
$currencyConverter = new CurrencyConverter($exchangeRate,$exchangeRate1);
View::dump($currencyConverter);
View::dump($currencyConverter->convert(299, $usd, $eur));
View::dump($currencyConverter->convert(299, $eur, $usd));
View::dump($currencyConverter->convert(299, $eur, $eur));

