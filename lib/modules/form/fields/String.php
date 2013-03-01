<?php
class FormFieldString extends FormField {

	/**
	 * Параметры поля
	 */
	public $max_length = 255;
	public $min_length = 0;
	public $pattern = "//";

	public function init($name, $is_required, $max_length = null, $min_length = null, $pattern = null) {
		return parent::registerField(func_get_args());
	}

	public function validate() {
		if ($this->is_required && !strlen($this->value)) {
			$this->addError("required");
		} elseif ($this->value) {
			if (strlen($this->value) > $this->max_length) {
				$this->addError("max_length");
			}
			if (strlen($this->value) < $this->min_length) {
				$this->addError("min_length");
			}
			if (!preg_match($this->pattern, $this->value)) {
				$this->addError("pattern");
			}
		}

		return !$this->errors;
	}

	public function proceedParams($params) {
		$params["maxlength"] = misc::is($params["maxlength"], $this->max_length);

		return parent::proceedParams($params);
	}
}

?>