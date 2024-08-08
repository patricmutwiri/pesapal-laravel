# Pesapal Package for Laravel Framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/patricmutwiri/pesapal.svg?style=flat-square)](https://packagist.org/packages/patricmutwiri/pesapal)
[![Total Downloads](https://img.shields.io/packagist/dt/patricmutwiri/pesapal.svg?style=flat-square)](https://packagist.org/packages/patricmutwiri/pesapal)
![GitHub Actions](https://github.com/patricmutwiri/pesapal-laravel/actions/workflows/main.yml/badge.svg)

This package is meant to help you integrate painlessly with Pesapal. In the end, you can receive payments through Pesapal API v3.0 in your application.

## Installation

You can install the package via composer:

```bash
composer require patricmutwiri/pesapal
```

## Usage

```php
// Load your invoice from DB, like
$invoice = Invoice::find(1);
// Then use it below
$paymentReq = new Request([
    'amount' => $invoice->balance,
    'email' => $invoice->user->email,
    'phone' => $invoice->user->phone,
    'first_name' => explode(' ', $invoice->user->name)[0],
    'last_name' => explode(' ', $invoice->user->name)[1],
    'id' => sprintf("%s-%s", $invoice->invoice_number, date('YmdHis')),
]);

// you can pass ipn_id above from your DB, or let the service add the latest one for you.

return Pesapal::payNow($paymentReq);
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dev@patric.xyz instead and log it under the issue tracker. All security vulnerabilities will be promptly addressed. Please do not disclose serious security-related issues publicly until a fix has been announced.

## Credits

-   [Patrick Mutwiri](https://github.com/patricmutwiri)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.