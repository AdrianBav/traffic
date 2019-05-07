# Bavanco Traffic Package
[![Build Status](https://travis-ci.com/AdrianBav/traffic.svg?branch=master)](https://travis-ci.com/AdrianBav/traffic)
[![StyleCI](https://github.styleci.io/repos/185431894/shield?branch=master)](https://github.styleci.io/repos/185431894)

Capture website traffic.

## Installation

Require the package using composer:

```bash
composer require adrianbav/traffic
```

## Usage

```php
use AdrianBav\Traffic\Facades\Traffic;

Traffic::record('site 1');
Traffic::record('site 2');

$visitCount = Traffic::visits();
```

## License
[MIT](./LICENSE.md)
