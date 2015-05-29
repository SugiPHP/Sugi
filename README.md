## Sugi ##

[![Build Status](https://travis-ci.org/SugiPHP/Sugi.svg)](https://travis-ci.org/SugiPHP/Sugi)

Sugi is a micro framework using SugiPHP Components as well as some other components. It works as a Dependency Injection Container and it is very flexible.

### Hello World! example ###

```php
<?php
$app = new \SugiPHP\Sugi\App();
$app->route("HelloWorld", "/hello", function () {
    echo "Hello World!";
});
$app->run();
?>
```

### Installation ###

```cli
composer require sugiphp/sugi:dev-master
```


## Sugi App ###

```php
<?php

use SugiPHP\Sugi\App;

// Instantiate SugiPHP Application:
$app = new App(["base_path" => dirname(__DIR__) . "/"]);

// Or use Singleton pattern:
$app = App::getInstance();
?>
```

### PSR-3 Logger

The App is using [SugiPHP\Logger](https://github.com/SugiPHP/Logger/tree/v2.x) by default, which is [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compliant. To use logger you can use one lazy method: `log(string $level, string $message, array $context = [])`, or use the instance `app["logger"]` to access methods given by the specification:

```php
<?php
    $app->log("debug", "Debug message");
    $app->log("info", "user input was", $_POST);

    $app["logger"]->error("my error message");
    $app["logger"]->setLevel("warning");
    $app["logger"]->info("this will not be logged");
?>
```


### PSR-7 requests

The App is PSR-7 compatible and is using [zendframework/zend-diactoros](https://github.com/zendframework/zend-diactoros) internally. You can plug any other PSR-7 compatible library for ServerRequest (instance of \Psr\Http\Message\ServerRequestInterface.)

```php
<?php

$app["request"] = new \Your\ServerRequest();

?>
```

The URI is an instance of \Psr\Http\Message\UriInterface, so you can use:

```php
<?php

$app["uri"]->getHost();
$app["uri"]->getPath();

?>
```
and all other PSR-7 UriInterface methods. Note that manipulating an $app["uri"] will not change it's value:

```php
<?php

echo $app["uri"]->getPath(); // "/"
echo $app["uri"]->withPath("/foo/bar")->getPath(); // "/foo/bar"
echo $app["uri"]->getPath(); // "/"

// to override it:
$app["uri"] = $app["uri"]->withPath("/foo");
echo $app["uri"]->getPath(); // "/foo"

?>
```
