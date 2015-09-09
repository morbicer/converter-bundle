<?php

namespace Sakiro\ConverterBundle\Provider;

use Money\Currency;
use Money\CurrencyPair;
use Sakiro\ConverterBundle\Exception\CurrencyRateException;

class ChainRateProvider implements RateProviderInterface
{
    /**
     * @var RateProviderInterface[]
     */
    protected $providers = array();

    public function __construct($config = array())
    {
    }


    public function getCurrencyPair(Currency $counterCurrency, Currency $baseCurrency)
    {
        foreach ($this->providers as $provider) {
            try {
                return $provider->getCurrencyPair($counterCurrency, $baseCurrency);
            }
            catch (\Exception $e) {
            }
        }

        throw new CurrencyRateException('No provider could provide the rate');
    }

    /**
     * Add a provider
     *
     * @param ProviderInterface $provider
     */
    public function addProvider(RateProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }



}