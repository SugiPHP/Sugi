<?php
/**
 * Logger configuration
 */


return array(
	array(
		"type"     => "file",
		"filename" => "log/custom-".date("Ymd").".log",
		// "filter"   => "all", // default is "all"
		"filter"   => "all -debug",
		// "format"   => "[{Y}-{m}-{d} {H}:{i}:{s}] [{ip}] [{level}] {message}", // default
	),
	array(
		"type"     => "stdout",
		"format"   => "[{datetime}] [{level}] {message}",
	),
	array(
		"type"     => "mail",
		"to"       => "webmaster@localhost",
		"subject"  => "sugi logger message",
		"from"     => "web@localhost",
		"filter"   => "none +emergency"
	),

);
