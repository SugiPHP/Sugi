<?php
/**
 * Bootstrap
 *
 * @package SugiPHP.Sugi
 */

use SugiPHP\Sugi\App;

$app = new App();

// Listen for 404 Not Found and show error page
$app->listen("404", function () {
    header("HTTP/1.0 404 Not Found");
    include "/path/to/404.html";
    exit;
});

$app->route("hello-world", "/", function ($request) {
    echo "Hello World!";
});

if (!$app->run()) {
    // page not found
    $app->fire("404");
}
