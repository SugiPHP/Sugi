<?php
use \SugiPHP\Sugi\Database;
use \SugiPHP\Sugi\Config;
use \SugiPHP\Sugi\Event;
use \SugiPHP\Sugi\Logger;

$loader = include "vendor/autoload.php";
$loader->add("SugiPHP\\Sugi", "../../");


// CONFIG
// Config::$path = "config".DIRECTORY_SEPARATOR;

// LOGGER
// $log = Logger::getInstance();
Logger::log("sys", "test me");

// EVENTS
Event::listen("sugi.database.post_open", function ($e) {
	Database::query("SET NAMES utf8");
});

Event::listen("sugi.database.pre_query", function ($e) {
	Logger::debug($e["query"]);
	echo ($e["query"]."<br />");
});

// DATABASE
// Database::$registerEvents = Config::get("database.registerEvents", true);
var_dump(Database::all("SHOW tables"));
// second database
$sqlite = Database::factory(Config::get("sqlite"));
$sqlite->query("CREATE table testtt(id integer not null);");
$sqlite->query("INSERT into testtt values (3);");
var_dump($sqlite->all("SELECT * FROM testtt"));


// CACHE

// SESSION

// ROUTER

