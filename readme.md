# Bavanco Traffic Package
[![Build Status](https://travis-ci.com/AdrianBav/traffic.svg?branch=master)](https://travis-ci.com/AdrianBav/traffic)
[![StyleCI](https://github.styleci.io/repos/185431894/shield?branch=master)](https://github.styleci.io/repos/185431894)

Capture website traffic.

## Installation

Require the package using composer:

```bash
composer require adrianbav/traffic
```

## Configuration

To publish the config, run the vendor publish command:

```bash
php artisan vendor:publish --provider="AdrianBav\Traffic\TrafficServiceProvider"
```

## Usage

This packages provides a middleware which can be added as a global middleware or as a single route.

```php
// in `app/Http/Kernel.php`

protected $middleware = [
    // ...
    
    \AdrianBav\Traffic\Middlewares\Traffic::class
];
```

```php
// in a routes file

Route::post('/article', function () {
    //
})->middleware(\AdrianBav\Traffic\Middlewares\Traffic::class);
```

Get a visit count for the specified site.

```php
use AdrianBav\Traffic\Facades\Traffic;

$blogVisitCount = Traffic::visits('blog_site_slug');
```

## License
[MIT](./LICENSE.md)
