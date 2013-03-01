<?php
class FormFieldFile extends FormField {

	/**
	 * Параметры поля
	 */
	public $max_size = 5242880;
	public $extentions = array("jpg", "jpeg", "gif", "png", "swf", "html", "css", "rtf", "doc", "xls", "txt", "zip", "rar", "csv", "docx", "xlsx", "pdf");

	/**
	 * Папка с временными файлами
	 */
	private $tmpDir = PROJECT_VAR_TMP;

	public function init($name, $is_required, $max_size = null, $extentions = null) {
		$this->parentForm->setMultipart();
		return parent::registerField(func_get_args());
	}

	public function validate() {
		if (!$this->value || file_exists($this->value)) {
			if ($this->is_required && !$this->value) {
				$this->addError("required");
			}

			if ($this->value) {
				if (filesize($this->value) > $this->max_size) {
					$this->addError("max_file_size");
				}
				$pathinfo = pathinfo($this->value);
				if (!in_array($pathinfo["extension"], $this->extentions)) {
					$this->addError("file_extension");
				}
			}
		}
		return !$this->errors;
	}

	public function proceedParams($params) {

		return parent::proceedParams($params);
	}

	public function setValue($value) {
		if (is_array($value)) {
			$item = $this->moveFile($value);
			$this->value = misc::is($item["name"], null);
		} else {
			$this->value = $value;
		}

		$this->value = str_replace("\\", "/", $this->value);

		$this->meta = array(
			"name" => end(split("\/", $this->value)),
			"tmp_name" => $this->value,
			"size" => filesize($this->value),
		);
		$this->copyTmp($this->value, $this->meta["name"]);
	}

	public function moveFile($item) {
		if ($item["name"] && !$item["error"]) {
			$pathinfo = pathinfo($item["name"]);

			$name = time().".".strtolower($pathinfo["extension"]);
			$tmp_name = $this->tmpDir.$name;
			move_uploaded_file($item["tmp_name"], $tmp_name);

			$item["name"] = end(split("\/", $item["name"]));
			$item["tmp_name"] = str_replace("\\", "/", $tmp_name);

			$this->copyTmp($tmp_name, $item["name"]);

		} else {
			$item = null;
		}
		return $item;
	}

	private function copyTmp($file, $name) {
		misc::check_dir(PROJECT_DATADIR."tmp");
		copy(str_replace("\\", "/", $file), PROJECT_DATADIR."tmp/".$name);
	}
}

?>