# jusibe-php-lib

[![Latest Stable Version](https://poser.pugx.org/unicodeveloper/jusibe-php-lib/v/stable.svg)](https://packagist.org/packages/unicodeveloper/jusibe-php-lib)
[![License](https://poser.pugx.org/unicodeveloper/jusibe-php-lib/license.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/unicodeveloper/jusibe-php-lib.svg)](https://travis-ci.org/unicodeveloper/jusibe-php-lib)
[![Quality Score](https://img.shields.io/scrutinizer/g/unicodeveloper/jusibe-php-lib.svg?style=flat-square)](https://scrutinizer-ci.com/g/unicodeveloper/jusibe-php-lib)
[![Total Downloads](https://img.shields.io/packagist/dt/unicodeveloper/jusibe-php-lib.svg?style=flat-square)](https://packagist.org/packages/unicodeveloper/jusibe-php-lib)

> Jusibe Library for PHP

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of jusibe-php, simply add the following line to the require block of your `composer.json` file.

```
"unicodeveloper/jusibe-php": "dev-master"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.


## Usage

### Send an SMS

```php

<?php

// include your composer dependencies
require_once 'vendor/autoload.php';

use Unicodeveloper\Jusibe\Jusibe;

$publicKey = 'xxxxxxxxxxxxxx';
$accessToken = 'xxxxxxxxxxxxxx';

$jusibe = new Jusibe($publicKey, $accessToken);

$message = "I LOVE YOU, BABY";

$payload = [
    'to' => '7079740987',
    'from' => 'PROSPER DATING NETWORK',
    'message' => $message
];

try {
    $response = $jusibe->sendSMS($payload)->getResponse();
} catch(Exception $e) {
    echo $e->getMessage();
}

[](https://cloud.githubusercontent.com/assets/2946769/14465033/451179c4-00c9-11e6-881e-bcc92665fa7c.png)

```


## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## How can I thank you?

Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter or HackerNews? Spread the word!

Don't forget to [follow me on twitter](https://twitter.com/unicodeveloper)!

Thanks!
Prosper Otemuyiwa.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
