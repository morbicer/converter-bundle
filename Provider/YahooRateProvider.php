<?php

namespace Morbicer\ConverterBundle\Provider;

use Money\Currency;
use Money\CurrencyPair;
use Morbicer\ConverterBundle\Exception\CurrencyRateException;

class YahooRateProvider implements RateProviderInterface
{
    const URL = 'http://finance.yahoo.com/d/quotes.csv';
    const QUERY = '?e=.csv&f=c4l1&s=%s%s=X';

    public function __construct($config = array())
    {
    }


    public function getCurrencyPair(Currency $counterCurrency, Currency $baseCurrency)
    {
        $from = $counterCurrency->getName();
        $to = $baseCurrency->getName();
        $pair = null;

        $url = self::URL.sprintf(self::QUERY, $from, $to);
        $csv = @file_get_contents($url);
        if ($csv == false) {
            throw new CurrencyRateException('Rate not avaiable, resource not found');
        }

        if ( preg_match('/"'.$to.'",([0-9\.]+)/i', $csv, $matches) ) {
            if ($matches && isset($matches[1])) {
                $ratio = (float)$matches[1];
            }
            else {
                throw new CurrencyRateException('Rate not avaiable, can not parse result: '.$csv);
            }

            $pair = new CurrencyPair($counterCurrency, $baseCurrency, $ratio);
        }
        else {
            throw new CurrencyRateException('Rate not avaiable, can not parse result: '.$csv);
        }

        return $pair;
    }



}