<?php

abstract class FormField {

	/**
	 * Тип поля, определяется автоматически
	 */
	public $type;

	/**
	 * Параметры поля
	 */
	public $name = "FormField";
	public $is_required = false;
	public $value;

	public $is_readonly = false;

	/**
	 * Список параметром инициализации формы
	 */
	private $init_param_list = array();

	/**
	 * Список параметров, передаваемых в шаблон
	 */
	private $tpl_param_list = array();

	/**
	 * Список ошибок валидации
	 */
	protected $errors = array();
	protected $errorTitle = array();

	/**
	 * Родительская форма
	 * @var LittleForm
	 */
	public $parentForm;

	public function __construct() {
		$oReflectionClass = new ReflectionClass(get_class($this));
        /* @var $oReflectionClass ReflectionClass */
		$oReflectionMethod = $oReflectionClass->getMethod("init");
        /* @var $oReflectionMethod ReflectionMethod */
		$oReflectionParameters = $oReflectionMethod->getParameters();

		for ($i = 0, $ci = sizeof($oReflectionParameters); $i<$ci; $i++) {
			$this->init_param_list[] = $oReflectionParameters[$i]->name;
		}

		$this->type = str_replace("FormField", "", get_class($this));
	}

	protected function registerField($arg_list) {
		$oField = clone $this;
		for ($i = 0, $ci = sizeof($this->init_param_list); $i<$ci; $i++) {
			$param_name = $this->init_param_list[$i];
			if (misc::is($arg_list[$i], null) !== null) {
				$oField->$param_name = $arg_list[$i];
			}
		}

		$this->parentForm->addField($oField);
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

	public function addError($code) {
		$this->errors[] = $code;
	}

	public function addErrorTitle($titles) {
		$this->errorTitle += $titles;
	}

	public function getErrorTitle() {
		return $this->errorTitle;
	}

	final function addParam($key, $value) {
		$this->tpl_param_list[$key] = $value;
	}

	public function proceedParams($params) {
		$params["name"] = $this->name;
		$params["value"] = $this->value;
		$params["id"] = $this->name;
		if ($this->is_readonly) {
			$params["readonly"] = "readonly";
		}

		if (isset($params["text_after"])) {
			$this->text_after = $params["text_after"];
			unset($params["text_after"]);
		}

		$params += $this->tpl_param_list;

		return $params;
	}

	final function getErrors() {
		return array_unique($this->errors);
	}

	function validate() {
		return true;
	}
}
?>