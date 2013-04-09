<?php

use \SugiPHP\Sugi\Database;
use \SugiPHP\Sugi\Config;
use \SugiPHP\Sugi\Event;
use \SugiPHP\Sugi\Logger;
use \SugiPHP\Sugi\Cache;

// CONFIG
// LOGGER
// EVENTS
// DATABASE
// CACHE
// TODO: SESSION

// TODO: ROUTER

// TODO: ASSETS

// TODO: I18N

// TODO: CRON

// TODO: IMAGE PROCESSING

// TODO: STOPWATCH




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
