<?php
require_once(dirname(__FILE__)."/String.php");

class FormFieldDate extends FormFieldString {

	public $max_length = 10;
	public $min_length = 0;
	public $pattern = "/^\d{1,2}[-.]\d{1,2}[-.]\d{4}$/";

	public function init($name, $is_required = false) {
		return parent::registerField(func_get_args());
	}

	public function validate() {
		parent::validate();

		if ($this->value && !strtotime($this->value)) {
			$this->addError("pattern");
		}

		return !$this->errors;
	}

	public function setValue($value) {
		if (!$value) {
			$res = "";
		} elseif ($res = strtotime($value)) {
			$res = date("d.m.Y", $res);
		} else {
			$res = $value;
		}
		$this->value = $res;
	}

	public function getValue() {
		if (!$this->value) {
			$res = null;
		} elseif ($res = strtotime($this->value)) {
			$res = date("Y-m-d", $res);
		} else {
			$res = $this->value;
		}

		return $res;
	}

}

?>