<?php
class FormFieldRadio extends FormField {

	/**
	 * Параметры поля
	 */
	public $options;

	public function init($name, $is_required, $options = array()) {
		return parent::registerField(func_get_args());
	}

	public function validate() {
		if ($this->is_required && !strlen($this->value)) {
			$this->addError("required");
		} elseif ($this->value && !key_exists($this->value, $this->options)) {
			$this->addError("wrong_value");
		}

		return !$this->errors;
	}

	public function proceedParams($params) {
		$params = parent::proceedParams($params);

		unset($params["value"]);
		unset($params["id"]);

		return $params;
	}

}

?>