# Currency for users of a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mechawrench/laracoins.svg?style=flat-square)](https://packagist.org/packages/mechawrench/laracoins)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mechawrench/laracoins/run-tests?label=tests)](https://github.com/mechawrench/laracoins/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/mechawrench/laracoins.svg?style=flat-square)](https://packagist.org/packages/mechawrench/laracoins)


Currency for application users.  Can be traded, sold, etc.

## Installation

You can install the package via composer:

```bash
composer require mechawrench/laracoins
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Mechawrench\Laracoins\LaracoinsServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Mechawrench\Laracoins\LaracoinsServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$skeleton = new Mechawrench\Laracoins();
echo $skeleton->echoPhrase('Hello, Mechawrench!');
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@mechawrench.be instead of using the issue tracker.

## Credits

- [Jesse Schneider](https://github.com/Mechawrench)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
