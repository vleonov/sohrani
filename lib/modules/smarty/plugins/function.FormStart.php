<?php

function smarty_function_FormStart($params, &$smarty) {
	$oForm = load("form", true);
	/*@var oForm form_factory*/

	return $oForm->tplStart($params);
}

?>