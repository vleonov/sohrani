<?php
require_once(dirname(__FILE__)."/String.php");

class FormFieldInteger extends FormFieldString {

	/**
	 * Параметры поля
	 */
	public $pattern = '/^\d+$/';

	public function init($name, $is_required, $max_length = null, $min_length = null) {
		return parent::registerField(func_get_args());
	}

	public function proceedParams($params) {
		$params["size"] = misc::is($params["size"], $this->max_length);

		return parent::proceedParams($params);
	}

}

?>