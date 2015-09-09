<?php

namespace Morbicer\ConverterBundle\Provider;

use Money\Currency;
use Money\CurrencyPair;
use Morbicer\ConverterBundle\Exception\CurrencyRateException;

class CurrencyApiRateProvider implements RateProviderInterface
{
    const URL = 'http://currency-api.appspot.com/api/%s/%s.json';

    public function __construct($config = array())
    {
    }

    public function getCurrencyPair(Currency $counterCurrency, Currency $baseCurrency)
    {
        $from = $counterCurrency->getName();
        $to = $baseCurrency->getName();
        $pair = null;

        $result = file_get_contents(sprintf(self::URL, $from, $to, $this->key));
        $result = json_decode($result);

        if ($result && isset($result->rate)) {
            $pair = new CurrencyPair($counterCurrency, $baseCurrency, $result->rate);
        }

        if ($pair == null) {
            throw new CurrencyRateException('Rate not avaiable, missing in result');
        }

        return $pair;
    }



}
