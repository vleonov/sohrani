<?php
class FormFieldSelect extends FormField {

	/**
	 * Параметры поля
	 */
	public $options;
	public $is_multiple;

	public function init($name, $is_required, $options = array(), $is_multiple = false) {
		return parent::registerField(func_get_args());
	}

	public function validate() {
		if ($this->is_required && !strlen($this->value)) {
			$this->addError("required");
		} elseif ($this->value) {
			if ($this->is_multiple) {
				$values = $this->value;
			} else {
				$values = array($this->value);
			}
			foreach ($values as $v) {
				if ($v && !key_exists($v, $this->options)) {
					$this->addError("wrong_value");
				}
			}
		}

		return !$this->errors;
	}

	public function proceedParams($params) {
		$params = parent::proceedParams($params);
		if ($this->is_multiple) {
			$params["name"] .= "[]";
		}

		return $params;
	}

	public function getValue() {
		return $this->value === "" ? null : $this->value;
	}
}

?>