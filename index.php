<?php

use \SugiPHP\Sugi\Database;
use \SugiPHP\Sugi\Config;
use \SugiPHP\Sugi\Event;
use \SugiPHP\Sugi\Logger;
use \SugiPHP\Sugi\Cache;
use \SugiPHP\Sugi\Router;
use \SugiPHP\HTTP\Request;

// CONFIG
// LOGGER
// EVENTS
// DATABASE
// CACHE
// 	ROUTER

// TODO: 
// 	ASSETS
// 	IMAGE PROCESSING - Imagine
// 	CRON
// 	I18N
// 	FILTER
// 	SESSION
// 	STOPWATCH


$loader = include "vendor/autoload.php";
$loader->add("SugiPHP\\Sugi", "../../");

// CONFIG
// Config::$path = "config".DIRECTORY_SEPARATOR;

// LOGGER
// $log = Logger::getInstance();
Logger::log("nsbop", "someone's testing");

// EVENTS
Event::listen("sugi.database.post_open", function ($e) {
	echo "MySQL connection established<br />";
	Database::query("SET NAMES utf8");
});

Event::listen("sugi.database.pre_query", function ($e) {
	Logger::debug($e["query"]);
});

// DATABASE
// Database::$registerEvents = Config::get("database.registerEvents", true);
if (!$alltables = Cache::get("testmysqlalltables")) {
	echo "Fetching tables<br />";
	$alltables = Database::all("SHOW tables");
	// CACHE
	if (Cache::set("testmysqlalltables", $alltables, 30)) {
		echo "Tables are cached<br />";
	}
} else {
	echo "Reading tables from cache<br />";
}
var_dump($alltables);

// second database
$sqlite = Database::factory(Config::get("sqlite"));
$sqlite->query("CREATE table testtt(id integer not null);");
$sqlite->query("INSERT into testtt values (3);");
var_dump($sqlite->all("SELECT * FROM testtt"));

// this will be emailed
// Logger::emergency("Some error occured when connecting to the database");


Router::add("mvc", "/{controller}/{action}/{param}", array("controller" => "home", "action" => "index", "param" => ""), array(), function($route) {
	var_dump($route);
});

$requests = array("/", "/show/123", "/user/login", "/path/too/long/to/work");
foreach($requests as $request) {
	if (Router::match(Request::custom($request))) {
		echo "<h2>".Router::getName()."</h2>";
	} else {
		echo "<h2>404<h2>";
	}
}
