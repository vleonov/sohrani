<?php
require_once(dirname(__FILE__)."/String.php");

class FormFieldText extends FormFieldString {

	/**
	 * Параметры поля
	 */
	public $max_length = 1000000;
	public $min_length = 0;

	public function init($name, $is_required = false, $max_length = null, $min_length = null) {
		return parent::registerField(func_get_args());
	}

}

?>