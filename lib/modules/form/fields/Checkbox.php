<?php
class FormFieldCheckbox extends FormField {

	/**
	 * Параметры поля
	 */
	public $group_values;

	public function isMultiple() {
	    return (bool)count($this->group_values);
	}

	public function init($name, $is_required = false, $group_values = array()) {
		return parent::registerField(func_get_args());
	}

	public function validate() {
	    if( $this->isMultiple() ) {
    		if( $this->is_required && !count( $this->getValue() )) {
    			$this->addError("required");
    	    }
	    }

	    return !$this->errors;
	}

	public function proceedParams($params) {
		$params = parent::proceedParams($params);
		if( $this->isMultiple() ) {
			unset($params["id"]);
			$params["name"] .= "[]";
		}

		return $params;
	}

	public function getValue() {
	    if( !$this->isMultiple() )
		    return $this->value ? true : false;
		else
		    return $this->value;
	}

}

?>