<?php

namespace Sakiro\ConverterBundle\Provider;

interface RateProviderInterface
{

    public function __construct($config);

    /**
     * Retrieve currency pair which holds the exchange ratio
     *
     * @param \Money\Currency $counterCurrency
     * @param \Money\Currency $baseCurrency
     * @return \Money\CurrencyPair pair with the ratio
     */
    public function getCurrencyPair(\Money\Currency $counterCurrency, \Money\Currency $baseCurrency);

}