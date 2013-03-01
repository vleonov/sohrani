<?php
class FormFieldHidden extends FormField {

	public function init($name) {
		return parent::registerField(func_get_args());
	}

	public function validate() {

		return true;
	}

}

?>