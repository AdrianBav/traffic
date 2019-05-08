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

```php
use AdrianBav\Traffic\Facades\Traffic;

Traffic::record(['visit1']);
Traffic::record(['visit2']);

$visitCount = Traffic::visits('traffic_site_slug');  // 2
```

## License
[MIT](./LICENSE.md)
