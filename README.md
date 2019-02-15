# Laravel Tinker Server

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcode/laravel-tinker-server.svg?style=flat-square)](https://packagist.org/packages/beyondcode/laravel-tinker-server)
[![Build Status](https://img.shields.io/travis/beyondcode/laravel-tinker-server/master.svg?style=flat-square)](https://travis-ci.org/beyondcode/laravel-tinker-server)
[![Quality Score](https://img.shields.io/scrutinizer/g/beyondcode/laravel-tinker-server.svg?style=flat-square)](https://scrutinizer-ci.com/g/beyondcode/laravel-tinker-server)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcode/laravel-tinker-server.svg?style=flat-square)](https://packagist.org/packages/beyondcode/laravel-tinker-server)

This package will give you a tinker server, that collects all your `tinker` call outputs **and** allows you to interact with the variables on the fly.

![](https://beyondco.de/github/tinker-server/tinker-server-demo.gif)

## About this package

This package was built as part of my [PHP Package Development](https://phppackagedevelopment.com) video course. Register for the course to learn how this package was built.

## Installation

You can install the package via composer:

```bash
composer require beyondcode/laravel-tinker-server
```

The package will register itself automatically.

Optionally you can publish the package configuration using:

```bash
php artisan vendor:publish --provider=BeyondCode\\LaravelTinkerServer\\LaravelTinkerServerServiceProvider
```

This will publish a file called `laravel-tinker-server.php` in your `config` folder.

In the config file, you can specify the dump server host that you want to listen on, in case you want to change the default value.

## Usage

Start the tinker server by calling the artisan command:

```bash
php artisan tinker-server
```

And then you can put `tinker` calls in your methods to dump variable content as well as instantly making them available in an interactive REPL shell.

```php
$user = App\User::find(1);

tinker($user);
```

In addition to the `tinker` method, there is also a `td` method, that behaves similar to `dd`. It tinkers the variable and dies the current request.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email marcel@beyondco.de instead of using the issue tracker.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
