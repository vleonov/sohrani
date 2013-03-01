<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


function smarty_modifier_beauty_address($address, $pattern = "street, number") {
	if (!is_array($address)) {
		$address = misc::parse_adress($address);
	}
	if (isset($address["house"])) {
		$address["number"] = $address["house"];
	}
	if (!empty($address["building"])) {
		$address["number"] .= " ê.".$address["building"];
	}
	$res = $pattern;
	foreach ($address as $k=>$v) {
		$res = str_replace($k, $v, $res);
	}
	return $res;
}

/* vim: set expandtab: */

?>
