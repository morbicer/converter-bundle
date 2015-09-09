<?php

namespace Sakiro\ConverterBundle\Service;

use Sakiro\ConverterBundle\Provider\RateProviderInterface;
use Doctrine\Common\Cache\Cache;
use Money\Money;
use Money\Currency;
use Money\CurrencyPair;

class Convert
{

    const CACHE_LIFETIME = 300;

    /**
     * @var RateProviderInterface
     */
    protected $provider;

    /**
     * @var Cache
     */
    protected $cache;


    public function __construct(RateProviderInterface $provider, Cache $cache = null)
    {
        $this->provider = $provider;
        $this->cache = $cache;
    }

    /**
     * @param \Money\Money|string $amount
     * @param \Money\Currency|string $from
     * @param \Money\Currency|string $to
     * @return \Money\Money
     */
    public function convert($amount, $from, $to)
    {
        if ($from instanceof Currency == false) {
            $from = new Currency(strtoupper($from));
        }
        if ($to instanceof Currency == false) {
            $to = new Currency(strtoupper($to));
        }

        if ($amount instanceof Money == false) {
            $money = new Money(Money::stringToUnits($amount), $from);
        }
        else {
            $money = $amount;
        }

        $pair = $this->fetchCached($from, $to);
        if ($pair == null) {
            $pair = $this->provider->getCurrencyPair($from, $to);
            $this->saveCached($pair);
        }

        $converted = $pair->convert($money);

        return $converted;
    }


    protected function getCacheKey(\Money\Currency $from, \Money\Currency $to)
    {
        return (string)$from.(string)$to;
    }

    protected function fetchCached(\Money\Currency $from, \Money\Currency $to)
    {
        if ($this->cache) {
            $cacheKey = $this->getCacheKey($from, $to);
            return $this->cache->fetch($cacheKey);
        }

    }

    protected function saveCached(\Money\CurrencyPair $pair)
    {
        if ($this->cache) {
            $cacheKey = $this->getCacheKey($pair->getCounterCurrency(), $pair->getBaseCurrency());
            $this->cache->save($cacheKey, $pair, self::CACHE_LIFETIME);
        }
    }
}

?>