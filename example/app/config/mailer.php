<?php

return array(
	"from"      => array("john@doe.com" => "John Doe"),
	"returnTo"  => "noreply@example.com",

	"transport" => "mail",
	"mail"      => array(),
	"sendmail"  => array(
		"path"      => "/usr/sbin/sendmail -bs",
	),
	"smtp"      => array(
		"host"      => "smtp.example.com",
		"port"      => 25, // optional defaults to 25
		"username"  => "your username", // optional
		"password"  => "your password", // optional
	)
);
