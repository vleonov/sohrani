<?php

function smarty_function_a($params, &$smarty) {

	if (isset($params["body"])) {
		$body = $params["body"];
		unset($params["body"]);
	} else {
		$body = "";
	}

	if (isset($params["href"]) && !isAllowUrl($params["href"])) {
		return "";
	}

	$res = "<a ";
	foreach ($params as $key=>$value) {
		$res .= $key.'="'.$value.'"';
	}
	$res .= ">".$body."</a>";

	return $res;
}

?>