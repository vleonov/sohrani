<?php
require_once(dirname(__FILE__)."/String.php");

class FormFieldPassword extends FormFieldString {

	public function init($name, $is_required) {
		return parent::registerField(func_get_args());
	}
}

?>