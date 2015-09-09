# converter-bundle
Currency converting bundle for Symfony2. Supports multiple exchange rate providers:
* Yahoo (free)
* Google (free)
* Currency API (free)
* chain (tries multiple if some is unavailable)

proper money handling using Martin Fowler's Money pattern implemented by [mathiasverraes/money](https://github.com/mathiasverraes/money)

## 1 Installation

### 1.1 Composer

```javascript
"require": {
  ....
  "morbicer/converter-bundle": "dev"
},
```

or

```
php composer.phar require morbicer/converter-bundle
```

### 1.2 Enable the bundle

```php
// app/AppKernel.php
public function registerBundles()
{
      $bundles = array(
        // ...
        new Morbicer\ConverterBundle\MorbicerConverterBundle(),
    );
}
```
	
### 1.3 Add config

```yaml
# app/config.yml
morbicer_converter:
  default_provider: chain
  providers:
      yahoo: []
      google: []
      currency_api: []
      chain: [yahoo, currency_api, google]
```
        
## Usage

```php
//in controller, get service
$converter = $this->get('morbicer_converter.convert');
// $100 USD to EUR
$converted = $converter->convert(100, 'USD', 'EUR');
$result = array(
    'amount' => $converted->getAmount()/100,
    'currency' => (string)$converted->getCurrency(),
);
```

