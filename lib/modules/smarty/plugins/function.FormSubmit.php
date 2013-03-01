<?php

function smarty_function_FormSubmit($params, &$smarty) {
	$oForm = load("form", true);
	/*@var oForm form_factory*/
	
	return $oForm->tplSubmit($params);
}

?>