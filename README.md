# Environment

This micro-component aims to make it easy to retrieve environment configuration
variables from as many different sources as possible.

```php
<?php
// Using PHP's own get_env() and put_env() functions.
$phpAdapter = new Environment\Adapter\Php;
// Using Memcache.
$memcacheAdapter = new Environment\Adapter\Memcache('127.0.0.1:11211');
// Using an array as stub.
$stubAdapter = new Environment\Adapter\Stub(["name"=>live, "readOnly"=>false]);
// You can make it try to retrieve information from multiple adapters.
$priorityQueue = [$memcacheAdapter, $phpAdapter, $stubAdapter]
$environment = new Environment\Reader($priorityQueue);
switch ($environment->name) {
    case 'live':
        // ...
        break;
    case 'developement':
        // ...
        break;
}
// Or test for just a given environment variable.
if ($environment->name->is('live')) {
    // ...
}

// Or maybe use the shortcut API
use Environment\Api as Env;

Env::alwaysReadFrom($priorityQueue);
if (Env::name->is('live')) {
    // ...
}
?>
```