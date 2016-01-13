## Mustard Auctions module

[![StyleCI](https://styleci.io/repos/45717903/shield?style=flat)](https://styleci.io/repos/45717903)
[![Build Status](https://travis-ci.org/hamjoint/mustard-auctions.svg)](https://travis-ci.org/hamjoint/mustard-auctions)
[![Total Downloads](https://poser.pugx.org/hamjoint/mustard-auctions/d/total.svg)](https://packagist.org/packages/hamjoint/mustard-auctions)
[![Latest Stable Version](https://poser.pugx.org/hamjoint/mustard-auctions/v/stable.svg)](https://packagist.org/packages/hamjoint/mustard-auctions)
[![Latest Unstable Version](https://poser.pugx.org/hamjoint/mustard-auctions/v/unstable.svg)](https://packagist.org/packages/hamjoint/mustard-auctions)
[![License](https://poser.pugx.org/hamjoint/mustard-auctions/license.svg)](https://packagist.org/packages/hamjoint/mustard-auctions)

Auctions support for [Mustard](http://withmustard.org/), the open source marketplace platform.

### Installation

#### Via Composer (using Packagist)

```sh
composer require hamjoint/mustard-auctions
```

Then add the Service Provider to config/app.php:

```php
Hamjoint\Mustard\Auctions\Providers\MustardAuctionsServiceProvider::class
```

### Licence

Mustard is free and gratis software licensed under the [GPL3 licence](https://www.gnu.org/licenses/gpl-3.0). This allows you to use Mustard for commercial purposes, but any derivative works (adaptations to the code) must also be released under the same licence. Mustard is built upon the [Laravel framework](http://laravel.com), which is licensed under the [MIT licence](http://opensource.org/licenses/MIT).
