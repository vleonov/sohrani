<?php

function smarty_function_FormField($params, &$smarty) {
	$oForm = load("form", true);
	/*@var oForm form_factory*/
	
	return $oForm->tplField($params);
}

?>