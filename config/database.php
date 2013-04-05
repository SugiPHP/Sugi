<?php

use SugiPHP\Sugi\Config;

return array(
	// "registerEvents" => false,
	"type" => "mysql",
	"mysql" => array(
		"database" => "test",
		// "host" => "127.0.0.1",
		// "user" => "test",
		// "pass" => "test",
	),
	"pgsql" => array(
		"database" => "test",
		"host" => "127.0.0.1",
		"user" => "test",
		"pass" => "test",
		"port" => 4321,
	),
	"sqlite" => Config::get("sqlite"),
);
