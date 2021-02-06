# LaraCoins

![LaraCoins Package](docs/LaraCoins.png "LaraCoins Package")

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mechawrench/laracoins.svg?style=flat-square)](https://packagist.org/packages/mechawrench/laracoins)
![Tests](https://github.com/mechawrench/laracoins/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/mechawrench/laracoins.svg?style=flat-square)](https://packagist.org/packages/mechawrench/laracoins)


WebApp currency, can be traded/sold/bought by users.

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

## Usage

``` php
// Import class (at top of file)
use Mechawrench\Laracoins;

// Fund user account
Laracoins::fundUser($user_id, $quantity, $comment);

// Lock and unlock user coins
Laracoins::lockUser($user_id);
Laracoins::unlockUser($user_id);

// Send coins between users
Laracoins::tradeCoins($to_user_id, $from_user_id, $amount, $comment);

// Get user balance
Laracoins::balance($user_id);

// Get user history
Laracoins::userHistory($user_id);

// Get top coin holders, sorted in desc order
Laracoins::topHolders();
Laracoins::topHolders($quantity);
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

If you discover any security related issues, please email jesse.schneider@hey.com instead of using the issue tracker.

## Credits

- [Jesse Schneider](https://github.com/Mechawrench)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
