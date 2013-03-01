<?php

function smarty_function_FormErrorTitle($params, &$smarty) {
	$oForm = load("form", true);
	/*@var oForm form_factory*/
	
	$name = misc::is($params["field"]);
	
	if ($name && sizeof($params)>1) {
		unset($params["field"]);
		$oForm->addErrorTitle($name, $params);
	}
	
	return false;
}

?>