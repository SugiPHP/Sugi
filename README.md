## Sugi ##

The second version of Sugi is using a different approach. It is a micro framework using SugiPHP Components as well as some other components.

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
composer require sugiphp/sugi
```


### Sugi App ###

```php
<?php

use SugiPHP\Sugi\App;

// Instantiate SugiPHP Application:
$app = new App(["base_path" => dirname(__DIR__) . "/"]);

// Or use Singleton pattern:
$app = App::getInstance();
?>
```

The App is PSR-7 compatible and is using [phly/http](https://github.com/phly/http) internally. You can plug any other PSR-7 compatible library for ServerRequest (instance of \Psr\Http\Message\ServerRequestInterface.)

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
