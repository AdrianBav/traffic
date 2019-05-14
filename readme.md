# Bavanco Traffic Package
[![Build Status](https://travis-ci.com/AdrianBav/traffic.svg?branch=master)](https://travis-ci.com/AdrianBav/traffic)
[![StyleCI](https://github.styleci.io/repos/185431894/shield?branch=master)](https://github.styleci.io/repos/185431894)

Capture website traffic.

## Installation

This package isn't on Packagist. Add it as a repository to the `composer.json` file:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/AdrianBav/traffic"
    }
]
```

Next, require the package using composer:

```bash
composer require adrianbav/traffic
```

## Configuration

Most configuration can be done via the environment variables.
However, to publish the config, run the vendor publish command:

```bash
php artisan vendor:publish --provider="AdrianBav\Traffic\TrafficServiceProvider"
```

To create the necessary tables, run the migrate command:

```bash
php artisan migrate
```

## Usage

This packages provides a middleware which can be added as a global middleware or as a single route.

```php
use AdrianBav\Traffic\Middlewares\RecordVisits;

// in `app/Http/Kernel.php`

protected $middleware = [
    // ...

    RecordVisits::class
];
```

```php
// in a routes file

Route::post('/article', function () {
    //
})->middleware(RecordVisits::class);
```

Get a visit count for the specified site.

```php
use AdrianBav\Traffic\Facades\Traffic;

$blogVisitCount = Traffic::visits('blog_site_slug');
```

## License
[MIT](./LICENSE.md)
