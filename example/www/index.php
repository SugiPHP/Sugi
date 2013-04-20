<?php
/**
 * Base entrance point
 * 
 * @package    SugiPHP
 * @subpackage Sugi
 */

namespace App;

use Sugi\Config;
use Sugi\Logger;
use Sugi\Event;
use Sugi\CSS;
use Sugi\JS;
use Sugi\File;
use Sugi\Cache;
use Sugi\Database;
use Sugi\Router;
use SugiPHP\HTTP\Request;

// start a timer
define("APPLICATION_START", microtime(true));

// shortcut
define("DS", DIRECTORY_SEPARATOR);

// Defining Working Directories
define("WWWPATH", __DIR__ . DS); // Document Root path (usually same as $_SERVER["DOCUMENT_ROOT"]);
define("BASEPATH", dirname(__DIR__) . DS); // Application Root path
define("APPPATH", dirname(__DIR__).DS."app".DS); // Application path

include "../../vendor/autoload.php";

// CONFIG
// LOGGER
// EVENTS
// DATABASE
// CACHE
// ROUTER
// ASSETS

// TODO: 
//  FILES
// 	FILTER
// 	SESSION
// 	IMAGE PROCESSING - Imagine
// 	CRON
// 	I18N
// 	STOPWATCH

// CONFIG
Config::$path = APPPATH."config".DS;

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

if (!File::exists(APPPATH."assets/test.css")) {
	throw \Exception("assets file test.css does not exists");
}
CSS::add("test.css");

// echo "<style>" . CSS::pack(false) . "</style>";
echo '<link rel="stylesheet" href="css/'.CSS::pack().'" />';

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
