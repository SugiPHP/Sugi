<?php

return array(
	"home" => array(
		"path"    => "/",
	),
	"news" => array(
		"path"       => "/show/{id}",
		"default"    => array("controller" => "article", "action" => "show"),
		"requisites" => array("id" => "\d+"),
	),
);
