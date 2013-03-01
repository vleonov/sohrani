<?php

function smarty_function_FormEnd($params, &$smarty) {
	$oForm = load("form", true);
	/*@var oForm form_factory*/
	
	return $oForm->tplEnd($params);
}

?>