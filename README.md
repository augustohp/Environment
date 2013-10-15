# Environment

This micro-component aims to make it easy to retrieve environment configuration
variables from as many different sources as possible.

```php
<?php
$defaultValues = [
    'ENVIRONMENT_NAME' => 'live',
    'MAINTENANCE_READ_ONLY' => false,
    "ENABLE_NEW_FEATURE" => false
]
$getEnv = new Environment\Source\GetEnv;
$default = new Environment\Source\ArrayObject($defaultValues);
$environment = new Environment\Reader([$getEnv, $default]);
switch ($environment->ENVIRONMENT_NAME) {
    case 'live':
        define('LOG_LEVEL', 'NOTICE');
        break;
    case 'developement':
        define('LOG_LEVEL', 'DEBUG');
        break;
}

// Or maybe use the shortcut API
use Environment\Api as Env;

Env::alwaysReadFrom([$getEnv, $default]);
if (Env::ENVIRONMENT_NAME->is('live')) {
    // ...
}
?>
```

## The competition

Here is a list of some projects I took a look before bothering myself to create
this one. If this library is not useful to you, some of these may come to be.

I also put some of the reasons I did not choose to use the given library, just
for my memory sake:

* [jalet/tvnu-config](http://jalet.github.io/tvnu-config/): Environment based configuration helper for multiple formats. INI, YAML, Arrays or Database (PDO).
    * No support to read from multiple environment sources at once
    * No support for `getenv()`
* [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv): Loads environment variables from .env to getenv(), $_ENV and $_SERVER automagically.
    * No support to read from multiple environment sources
* [brianium/habitat](https://github.com/brianium/habitat): Because we dont always know if $_ENV is available.
    * No support to read from multiple environment sources (only `getenv()`)
* [titon/env](https://github.com/titon/env): Multiple environment configuration and bootstrapping support.
    * No support for existing environment configuration
* [thesmart/php-environment](https://github.com/thesmart/php-environment): Easily manage database connection strings or other configuration.
    * No support for existing environment configuration
    * No license
* [neemzy/environ](https://github.com/neemzy/environ): PHP 5.3+ lightweight environment manager/helper.
    * No support for existing environment configuration
    * No tests
    * Lack of global (static) API easing bootstrap and use
* [slavahatnuke/environment](https://github.com/slavahatnuke/environment): Easy check your environment and build it in one action.
    * No Object Oriented API to do the checks (straightforward at least)
    * No tests
    * More of a [chef][]/[puppet][] like tool
    * Poor documentation
* [fermio/Environment](https://github.com/fermio/Environment): Developer friendly Symfony environment configuration.
    * Only for Symfony 2
    * No documentation