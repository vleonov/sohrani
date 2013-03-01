<?php

/**
 * Общая структура сервиса
 *
 */
$structure = array(

	"/" => array(
		'controller' => "C_Main",
		'action' => "index",
		'title' 	=> "Sohrani.info",
	),

	"/ajax/card/save/" => array(
		'controller' => "C_Card",
		'action' => "save",
	),

	"/ajax/card/read/" => array(
		'controller' => "C_Card",
		'action' => "read",
	),
	"/ajax/card/del/:id/" => array(
		'controller' => "C_Card",
		'action' => "del",
		"required" => array(":id" => "\d+"),
	),

);

?>