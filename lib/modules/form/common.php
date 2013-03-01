<?

class form_common {

	/**
	 * Дефолтные параметры формы
	 */
	public $name = "Form";
	public $method = "POST";
	public $action = THIS_URL;
	public $value = "Сохранить";

	/**
	 * Возможные поля формы
	 */
	/**
	 * @var FormFieldString  Поле Строка
	 */
	public $String;

	/**
	 * @var FormFieldText  Поле Текст
	 */
	public $Text;

	/**
	 * @var FormFieldInteger  Поле Целое число
	 */
	public $Integer;

	/**
	 * @var FormFieldCheckbox  Поле Чекбокс
	 */
	public $Checkbox;

	/**
	 * @var FormFieldSelect  Поле Селект
	 */
	public $Select;

	/**
	 * @var FormFieldPassword  Поле Пароль
	 */
	public $Password;

	/**
	 * @var FormFieldFile  Поле Файл
	 */
	public $File;

	/**
	 * @var FormFieldRadio  Поле Радиобатон
	 */
	public $Radio;

	/**
	 * @var FormFieldWiki  Поле Wiki
	 */
	public $Wiki;

	/**
	 * @var FormFieldHidden  Скрытое поле
	 */
	public $Hidden;


	/**
	 * Список форм
	 */
	private $list = array();

	/**
	 * Текущая форма
	 */
	private $currForm;

	/**
	 * Папка с шаблонами формы
	 */
	private $tplDir = "common/Form/";

	public function getInstance() {
		return $this;
	}


	/**
	 * Инициализация
	 */
	public function __construct() {
		$dir = dirname(__FILE__);
		require_once($dir."/field.php");
		$dir .= "/fields";
		$file_list = scandir($dir);
		for ($i = 0, $ci = sizeof($file_list); $i<$ci; $i++) {
			if (is_file($dir."/".$file_list[$i])) {
				require_once($dir."/".$file_list[$i]);
				$field_name = substr($file_list[$i], 0, strpos($file_list[$i], "."));
				$class_name = "FormField".$field_name;
				$this->$field_name = new $class_name();
				$this->$field_name->parentForm = $this;
			}
		}

	}

	public function init($name = null, $method = null, $action = null, $back_url = null) {
		$name = $name ? $name : $this->name."_".sizeof($this->list);
		$back_url_alternative = misc::is($_REQUEST["form_back_url"],
			misc::is( $_SERVER["HTTP_REFERER"], '' ) );
		$this->list[$name] = array(
			"name" => $name,
			"method" => $method ? $method : $this->method,
			"action" => $action ? $action : $this->action,
			"back_url" => $back_url ? ROOT_URL.trim($back_url, "/") : $back_url_alternative,
			"fields" => array(),
		);
		$this->currForm = $name;
	}

	public function back_url() {
		return $this->list[$this->currForm ? $this->currForm : key($this->list)]["back_url"];
	}

	public function addField($oField) {
		$this->list[$this->currForm]["fields"][$oField->name] = $oField;
	}

	public function getFieldList($name = null) {
		return $this->list[ ($name ? $name : $this->currForm) ]["fields"];
	}

	public function setValues($data) {
		$this->currForm = misc::is($this->currForm, key($this->list));
		$form = &$this->list[$this->currForm];
		$this->currForm;

		foreach ($data as $key=>$value) {
			if (isset($form["fields"][$key])) {
				$form["fields"][$key]->setValue($value);
			}
		}
	}

	public function setMultipart() {
		$this->list[$this->currForm]["enctype"] = "multipart/form-data";
		$this->list[$this->currForm]["method"] = "POST";
	}

	public function setReadonly($fields, $value = true) {
		if (!is_array($fields)) {
			$fields = array($fields);
		}
		foreach ($fields as $field) {
			$oField = $this->list[$this->currForm]["fields"][$field];
			$oField->is_readonly = $value;
		}
	}

	public function addError($field, $code) {
		$form = $this->list[$this->currForm];

		if (isset($form["fields"][$field])) {
			$form["fields"][$field]->addError($code);
		}
	}

	public function addErrorTitle($field_name, $error_title) {
		$form = $this->list[$this->currForm];

		$form["fields"][$field_name]->addErrorTitle($error_title);
	}

	/**
	 * Прием и проверка данных
	 * @param string $form_name Имя обрабатываемой формы
	 */

	public function isForm( $form_name = null ) {
		$current_name = misc::is($_REQUEST["form_name"], null);
		if ($current_name && isset($this->list[$current_name])) {
			$res = true;
			$this->currForm = $current_name;

			if (!empty($_REQUEST["form_action"])) {
				switch ($_REQUEST["form_action"]) {
					case "validate":
						//Запрос на валидацию данных, но не на отправку их
						$this->processValidate();
						break;
					case "upload":
						//Запрос на загрузку файла, но не на отправку данных
						$this->processUpload();
						break;
				}
			}

		} else {
			$res = false;
		}

		return $res;
	}

	public function isValid($is_iconv = false) {
		$form = $this->list[$this->currForm];

		$data = strtoupper($form["method"]) == "POST" ? $_POST : $_GET;
		if ($is_iconv) {
			$data = misc::iconv($data, "UTF-8", "WINDOWS-1251");
		}
		if (isset($_FILES)) {
			$data += $_FILES;
		}
		$res = true;

		foreach ($form["fields"] as $oField) {
			if ($oField instanceof FormFieldFile && !empty($data[$oField->name])) {
				$oField->setValue($data[$oField->name]);
			} else {
		        $oField->value = misc::is($data[$oField->name], null);
			}
			$res &= $oField->validate();
		}

		return $res;
	}

	public function getValues() {
		$form = $this->list[$this->currForm];

		$res= array();
		foreach ($form["fields"] as $oField) {
			$res[$oField->name] = $oField->getValue();
		}

		return $res;
	}


	/**
	 * Вывод
	 */
	public function tplStart($params) {
		$this->currForm = misc::is($params["name"], key($this->list));
		$form = $this->list[$this->currForm];
		$this->is_submit_shown = false;

		$params["name"] = $form["name"];
		$params["action"] = $form["action"];
		$params["method"] = $form["method"];
		if (isset($form["enctype"])) {
			$params["enctype"] = $form["enctype"];
		}

		return $this->tpl("form_start.tpl", array("params" => $params, "form" => $form));
	}

	public function tplField($params) {
		$form = &$this->list[$this->currForm];

		if (!empty($params["name"])) {
			$oField = $form["fields"][$params["name"]];
		} else {
			$oField = current($form["fields"]);
			next($form["fields"]);
		}
		$params = $oField->proceedParams($params);

		return $this->tpl("form_field.tpl", array("params" => $params, "oField" => $oField));
	}

	public function tplSubmit($params) {
		$form = $this->list[$this->currForm];
		$this->is_submit_shown = true;

		$params["value"] = misc::is($params["value"], $this->value);

		return $this->tpl("form_submit.tpl", array("params" => $params, "form" => $form));
	}

	public function tplEnd($params) {
		$res = "";
		if (empty($this->is_submit_shown)) {
			$res .= $this->tplSubmit($params);
		}

		$form = $this->list[$this->currForm];
		$this->currForm = null;

		$res .= $this->tpl("form_end.tpl", array("params" => $params, "form" => $form));

		return $res;
	}

	public function tpl($tpl_name, $params) {
		$oSmarty = load("smarty", true);
		/*@var oSmarty smarty_common*/

		$tpl_dir = $oSmarty->template_dir;
		$oSmarty->template_dir = PROJECT_TEMPLATE_CMFSDIR;

		$oSmarty->assign($params);
		$res = $oSmarty->fetch($this->tplDir.$tpl_name);

		$oSmarty->template_dir = $tpl_dir;

		return $res;
	}


	private function processValidate() {
		$form = $this->list[$this->currForm];

		$is_valid = $this->isValid(true);
		$data = array(
			"is_valid" => $is_valid,
			"errors" => array(),
		);
		foreach ($form["fields"] as $oField) {
			if ($oField->getErrors()) {
				$data["errors"][$oField->name] = $oField->getErrors();
			}
		}

		$res = $this->tpl("form_validate.tpl", $data);

		die($res);
		die($res);
	}

	private function processUpload() {
		$form = $this->list[$this->currForm];
		$item = null;
		foreach ($_FILES as $name=>$f) {
			if (isset($form["fields"][$name]) && $form["fields"][$name] instanceof FormFieldFile) {
				$item = $f;
				break;
			}
		}
		if ($item && !$item["error"]) {
			$item = $form["fields"][$name]->moveFile($item);
		} else {
			$item = null;
		}

		$res = $this->tpl("form_upload.tpl", array("name" => $name, "item" => $item, "form" => $form));
		die($res);
	}

}
?>