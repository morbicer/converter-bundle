<?php

namespace Morbicer\ConverterBundle\Provider;

use Money\Currency;
use Money\CurrencyPair;
use Morbicer\ConverterBundle\Exception\CurrencyRateException;

class GoogleRateProvider implements RateProviderInterface
{
    //TODO obsolete since Nov 2013
    const URL = 'http://www.google.com/finance/converter';
    const QUERY = '?a=1&from=%s&to=%s';

    public function __construct($config = array())
    {
    }


    public function getCurrencyPair(Currency $counterCurrency, Currency $baseCurrency)
    {
        $from = $counterCurrency->getName();
        $to = $baseCurrency->getName();
        $pair = null;

        $url = self::URL.sprintf(self::QUERY, $from, $to);
        $html = @file_get_contents($url);
        if ($html == false) {
            throw new CurrencyRateException('Rate not avaiable, resource not found');
        }

        $doc = new \DOMDocument();
        @$doc->loadHTML($html);
        $el =$doc->getElementById('currency_converter_result');

        if ($el && $el->hasChildNodes()) {
            foreach ($el->childNodes as $node) {
                if ($node->nodeName == 'span' && preg_match('/[0-9\.]+ '.$to.'/i', $node->textContent, $matches)) {
                    $ratio = (float)$matches[0];
                    $pair = new CurrencyPair($counterCurrency, $baseCurrency, $ratio);
                }
            }
        }

        if (!$pair) {
            throw new CurrencyRateException('Rate not avaiable, can not parse result: '.$html);
        }

        return $pair;
    }



}