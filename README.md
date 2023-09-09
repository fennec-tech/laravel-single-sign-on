# This package is a very simple Laravel single sign on for multiple applications.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fennec-tech/laravel-single-sign-on.svg?style=flat-square)](https://packagist.org/packages/fennec-tech/laravel-single-sign-on)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/fennec-tech/laravel-single-sign-on/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/fennec-tech/laravel-single-sign-on/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/fennec-tech/laravel-single-sign-on/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/fennec-tech/laravel-single-sign-on/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/fennec-tech/laravel-single-sign-on.svg?style=flat-square)](https://packagist.org/packages/fennec-tech/laravel-single-sign-on)

This package is a very simple Laravel single sign on for multiple applications.

## Installation

You can install the package via composer:

```bash
composer require fennec-tech/laravel-single-sign-on
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-single-sign-on-config"
```

## Usage

1- Add `SESSION_DOMAIN` and `ACCOUNTS_URL` to your .env file.

2- Add `LaravelSingleSignOnMiddleware::class` to the `$middleware` variable in the `kernel.php` file.

3- Add `RedirectIfAuthenticatedMiddleware::class` to the `'guest'` attribute in the `$routeMiddleware` variable in the `kernel.php` file.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [Aymen Meziani](https://github.com/aymenmeziani)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
