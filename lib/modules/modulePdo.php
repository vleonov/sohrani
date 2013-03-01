<?php

/**
 * Класс для отображения объектов модуля в БД
 */

class modulePdo extends module {

	/**
	 * Объект класса PDO
	 */
	private $oDb;

	private $param_def = array();

	private $isStructureLoaded = false;

	public function __construct() {
		$this->oDb = load("pdo", true);
	}

	/**
	 * SELECT объекта из таблицы
	 */
	final public function get($object, $id) {
		$this->checkObject($object);

		$sql = "SELECT * FROM :table:T WHERE id=:id";
		$params = array(
			"table" => $this->getTableName($object),
			"id" => $id,
		);
		$res = $this->select($object, $sql, $params, true);

		return $res;
	}

	/**
	 * SELECT с выборкой по каким-либо полям
	 */
	final public function getByParams($object, $params) {
		$this->checkObject($object);

		$where = array();
		foreach ($params as $key=>$value) {
			$where[] = $key." = :".$key;
		}

		$where = $where ? " WHERE ".implode(" AND ", $where) : "";
		$sql = "SELECT * FROM :table:T".$where;

		$params += array(
			"table" => $this->getTableName($object),
		);
		$res = $this->select($object, $sql, $params);

		return $res;
	}

	/**
	 * UPDATE или INSERT объекта в таблицу
	 */
	final public function set($object, $data, $id = null) {
		$this->checkObject($object);

		$session_id = ( isset( $_SESSION["id"] ) ? $_SESSION["id"] : null );
		if ($id === null) {
			$data["created_by"] = misc::is($data["created_by"], $session_id);
			$data["created_at"] = misc::is($data["created_at"], date("Y-m-d H:i:s"));
		}
		$data["modified_by"] = misc::is($data["modified_by"], $session_id);
		$data["modified_at"] = misc::is($data["modified_at"], date("Y-m-d H:i:s"));

		foreach ($data as $field => $value) {
			if (!isset($this->objects[$object]["fields"][$field])) {
				unset($data[$field]);
			}
		}

		if (!sizeof($data)) {
			return false;
		}

		$this->oDb->beginTransaction();

		if ($id !== null) {
			$this->logChanges($object, $data, "update", $id);

			$sql = array();
			foreach ($data as $field => $value) {
				$sql[] = $this->oDb->tquote($field)."=:".$field.":".$this->objects[$object]["fields"][$field];
			}
			$sql = "UPDATE :table:T SET ".implode(", ", $sql)." WHERE id=:id:D";
			$params = $data + array(
				"table" => $this->getTableName($object),
				"id" => $id,
			);
			$this->oDb->exec($sql, $params);

			$res = $id;

		} else {
			$sql = array();
			foreach ($data as $field => $value) {
				$sql[] = ":".$field.":".$this->objects[$object]["fields"][$field];
			}
			$fields = array_map(array($this->oDb, "tquote"), array_keys($data));
			$sql = "INSERT INTO :table:T (".implode(", ", $fields).") VALUES (".implode(", ", $sql).")";
			$params = $data + array(
				"table" => $this->getTableName($object),
			);
			$res = $this->oDb->exec($sql, $params);

			if ($res) {
				$id = $this->oDb->lastInsertId($params["table"]."_id_seq");
				$res = $id;
			}

			$this->logChanges($object, $data, "create", $id);
		}

		$this->oDb->commit();

		return $res;
	}

	/**
	 * Удаление объекта из таблицы
	 */
	public function delete($object, $id) {
		$this->checkObject($object);

		$this->oDb->beginTransaction();

		$this->logChanges($object, array(":delete" => null), "delete", $id);

		$sql = "DELETE FROM :table:T WHERE id=:id";
		$params = array(
			"table" => $this->getTableName($object),
			"id" => $id,
		);
		$res = $this->oDb->exec($sql, $params);

		$this->oDb->commit();

		return $res;
	}

	/**
	 * Общий метод для выборки
	 */
	final public function select($object, $sql, $params, $is_once = false) {
		$this->checkObject($object);

		$params += $this->param_def;

		$res = $this->oDb->select($sql, $params, $is_once);

		return $res;
	}

	/**
	 * Общий метод для поиска строк по набору параметров
	 */
	final public function search($object, $params = array(), $order = array()) {
		$this->checkObject($object);

		$where = array();
		foreach($params as $field=>$v) {
			if (is_array($v)) {
				$where[] = $field." IN (:".$field.":L)";
			} elseif (isset($this->objects[$object]["fields"][$field])) {
				$where[] = $field."=:".$field.":".$this->objects[$object]["fields"][$field];
			} else {
				$where[] = $field."=:".$field;
			}
		}
		$where = $where ? " WHERE ".implode(" AND ", $where) : "";
		$sql = "SELECT * FROM :table:T".$where.($order ? " ORDER BY :order:O" : "");
		$params["table"] = $this->getTableName($object);
		$params["order"] = $order;
		$res = $this->select($object, $sql, $params);

		return $res;
	}

	final public function checkObject($object) {
		/**
		 * Считаем структуру БД
		 */
		if (!$this->isStructureLoaded && sizeof($this->objects)) {
			$this->isStructureLoaded = true;
			$params = array();
			foreach ($this->objects as $object => &$data) {
				$params["table_list"][] = $this->getTableName($object);
			}
			$params["schema"] = $this->oDb->extract("dbname");

			foreach ($this->objects as $object => &$data) {
					$table_name = $this->getTableName($object);
					$sql = "DESCRIBE " . $table_name;
					$s = $this->oDb->select($sql, array());
					for ($i = 0, $ci = sizeof($s); $i<$ci; $i++) {
							$this->objects[$object] ["fields"] [$s[$i]["Field"]] = $this->oDb->parseType($s[$i]["Type"]);
					}
			}

			foreach ($this->objects as $k=>$v) {
				$this->param_def["tbl_".$k] = $this->getTableName($k);
			}

		}

		return parent::checkObject($object);
	}

	/**
	 * Определяет название таблицы исходя из объекта
	 */
	final public function getTableName($object) {

		$res = $this->getObjectPrefix($object).$object;

		return $res;
	}

	final public function getObjectPrefix($object) {
		$this->checkObject($object);

		if (isset($this->objects[$object]["params"]) && isset($this->objects[$object]["params"]["prefix"])) {
			$res = $this->objects[$object]["params"]["prefix"];
		} elseif (isset($this->prefix)) {
			$res = $this->prefix;
		} else {
			$res = "";
		}

		return $res;
	}

	final private function getObjectName($table) {
		$res = null;

		foreach ($this->objects as $object => &$data) {
			if ($this->getTableName($object) == $table) {
				$res = $object;
				break;
			}
		}

		return $res;
	}

	final private function logChanges($object, $data, $action, $id = null) {
																		//Логгируем изменения, если
		if (defined("LOGGER_ON")										// ... включено общее логгирование (т.е. логгирование всего)
				|| (													// ... или
					!empty($this->objects[$object]["params"])			// ... включено логгирование именно этого объекта
						&&
					!empty($this->objects[$object]["params"]["log"])
				)
			) {
			$oLogger = load("logger", true);

			$oLogger->log($this, $data, $action, $id, $object);

		}
	}

	/**
	 * Устанавливает позицию объекта внутри родителя
	 *
	 * @param  $object  string  Объект для установки позиции
	 * @param  $id  int  id объекта
	 * @param  $pos  mixed  Номер позиции, если null - значит последнее место, если +1/-1, то увеличить на 1/уменьшить на 1
	 * @return boolean
	 */
	public function setPosition($object, $id, $pos = null) {
		$oDb = load("pdo", true);
		/*@var $oDb pdo_common*/

		switch ($object) {
			case "category":
				$field = "parent_id";
				break;
			case "question":
				$field = "category_id";
				break;
			default:
				return false;
		}
		$item = $this->get($object, $id);
		$params = array(
			"tbl" => $this->getTableName($object),
			"field" => $field,
			"p_id" => $item[$field],
		);
		$where = " WHERE :field:T ".($params["p_id"] ? "= :p_id:D" : "IS NULL")." ";

		//Определение позицию, на которую нужно установить объект
		if ($pos === null) {
			$sql = "SELECT MAX(position) FROM :tbl:T ".$where;
			$pos = $this->select($object, $sql, $params, true);
			$pos = $pos["max"]+1;
		} elseif ($pos === "+1") {
			$pos = $item["position"] + 1;
		} elseif ($pos === "-1") {
			$pos = $item["position"] - 1;
		} else {
			$pos = intval($pos);
		}
		$pos = max(1, $pos);

		//Изменение позиций объектов, которые придется подвинуть
		if ($item["position"] !== null) {
			//Смена позиции
			$where .= "AND position BETWEEN :pos1 AND :pos2";
			if ($item["position"] > $pos) {
				//вверх
				$sql = "UPDATE :tbl:T SET position = position+1 ".$where;
				$params["pos1"] = $pos;
				$params["pos2"] = $item["position"];
			} else {
				//вниз
				$sql = "UPDATE :tbl:T SET position = position-1 ".$where;
				$params["pos1"] = $item["position"];
				$params["pos2"] = $pos;
			}
		} else {
			//Установка новой позиции
			$where .= "AND position >= :pos";
			$params["pos"] = $pos;
			$sql = "UPDATE :tbl:T SET position = position+1 ".$where;
		}
		$oDb->exec($sql, $params);

		//Установка позиции
		$this->set($object, array("position" => $pos), $id);

		return true;
	}

}

?>