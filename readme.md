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
use AdrianBav\Traffic\Traffic;

$traffic = new Traffic;

$traffic->record('site 1');
$traffic->record('site 2');

$visitCount = $traffic->visits();
```

## License
[MIT](./LICENSE.md)
