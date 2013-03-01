<?

class pdo_common extends PDO {
	
	protected $oPlaceholders;
	
	protected $dbDsn;
	protected $dbType;
	
	protected $isConnected = false;
	
	private $transactionCnt = 0;
	
	function __construct($dsn=DSN) {

		$dsn_p = explode(":", $dsn);
		$this->dbType = strtolower(misc::is($dsn_p[0]));
		$this->dbDsn = $dsn;
		
	} // end default constructor
	
	public function extract($key) {
		$result = '';
		if (preg_match('/(:|;)' . $key . '=([^\s]+?)(;|$)/', $this->dbDsn, $match)) {
			$result = $match[2];
		}
		return $result;
	
	}
	
	private function connect() {
		if ($this->dbType == "mysql") {
			$user = $this->extract("user");
			$password = $this->extract("password");
			parent::__construct($this->dbDsn, $user, $password);
		} else {
			parent::__construct($this->dbDsn);
		}
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->oPlaceholders = new Pdo_Placeholders($this, $this->dbType);
		
		$this->isConnected = true;
		$this->exec('SET NAMES "UTF8"'); 
	}
	
	private function checkConnect() {
		if (!$this->isConnected) {
			$this->connect();
		}
	}

	public function getInstance() {
		return $this;
	}

	public function getNewInstance($dsn) {
		return new pdo_common($dsn);
	}

	public function Execute($sql, $once = false) {
		$this->checkConnect();

		$result = $this->query($sql);
		$rs = $result->fetchAll(PDO::FETCH_ASSOC);
		if ($once) {
	    	$rs = isset($rs[0]) ? $rs[0] : '';
		}
		
		return $rs;		
	} // end Execute()
	
	public function select($sql, $params, $is_once = false) {
		$this->checkConnect();

		$query = $this->prepareAndBind($sql, $params);
		$rs = $this->query($query);
		
		if ($rs) {
			$res = $rs->fetchAll(PDO::FETCH_ASSOC);

			if ($res && $is_once) {
				$res = $res[0];
			}

		} else {
			$res = false;
		}
		
		return $res;
	}
	
	public function exec($sql, $params = null) {
		$this->checkConnect();

		if ($params) {
			$query = $this->prepareAndBind($sql, $params);
			$rs = parent::exec($query);
		} else {
			$rs = parent::exec($sql);
		}
		return $rs;
	}
	
	public function beginTransaction() {
		$this->checkConnect();

		if ($this->transactionCnt++ == 0) {
			$res = parent::beginTransaction();
		} else {
			$res = true;
		}
		return $res;
	}

	public function commit() {
		$this->checkConnect();

		if ($this->transactionCnt-- == 1) {
			$res = parent::commit();
		} else {
			$res = true;
		}
		return $res;	
	}

	public function rollback($is_full = false) {
		$this->checkConnect();

		if ($this->transactionCnt-- == 1 || $is_full) {
			$res = parent::rollback();
			$this->transactionCnt = 0;
		} else {
			$res = true;
		}
		return $res;
	}
	
	public function parseType($type) {
		$type = strtolower($type);
		
		if ($type == "int" || $type == "smallint" || $type == "bigint") {
			$res = "D";
		} elseif ($type == "decimal" || $type == "double" || $type == "float") {
			$res = "F";
		} else {
			$res = "S";
		}
		
		return $res;
	}
	
	private function prepareAndBind($sql, $params) {
		//������ ��� �������

		$oRes = $this->oPlaceholders->expandPlaceholders($sql, $params);
		
		return $oRes->query;
	}
	
	public function quote($value, $param_type = PDO::PARAM_STR) {
		$result = '';
		switch($this->dbType) {
			case "pgsql":
				$result = pg_escape_string($value);
				break;

			case "mysql":
				$result = mysql_escape_string($value);
				break;

			default:
				throw new E_DbCmf_Exception("Unknown database type: \"".$this->dbType."\"");
				break;
		}
		if ($param_type === PDO::PARAM_STR) {
		    $result = "'" . $result . "'";
		}
		return $result;

	}

	public function tquote($name) {
		// $name = $this->oPdo->quote($name, PDO::PARAM_STR);
		$name = $this->quote($name, PDO::PARAM_INT);
		switch ($this->dbType) {
			case "pgsql":
				$qchar = '"';
				break;
			case "mysql":
				$qchar = "`";
				break;
			default:
				throw new E_Pdo_Exception("Unknown database type: \"".$this->dbType."\"");
				break;
		}
		$name = str_replace($qchar, $qchar.$qchar, $name);
		$name = $qchar . $name . $qchar;
		return $name;
	}
	
} // end class pdo_common

class E_Pdo_Exception extends Exception {}

class Pdo_Placeholders_Result {
	public $query;
	public $tables;

	public function __construct($query, $tables) {
		$this->query = $query;
		$this->tables = $tables;
	}
}

class Pdo_Placeholders {
	protected $oPdo;
	protected $dbType;
	protected $ph_params;
	protected $ph_params2;
	protected $tables = array();

	public function __construct(PDO $oPdo, $dbType) {
		$this->oPdo = $oPdo;
		$this->dbType = $dbType;
	}

	public function expandPlaceholders($query, $ph_params) {
		$this->ph_params = $ph_params;
		$this->ph_params2 = array_reverse($ph_params);
		$this->tables = array();

		$query = preg_replace_callback(
			'/(?:\:([A-Za-z0-9_]*)(?::([A-Z]))?)/sx',
			array($this, '_expandPlaceholdersCallback'),
			$query
        );

        return new Pdo_Placeholders_Result($query, array_keys($this->tables));
	}

	public function _expandPlaceholdersCallback($m) {
		$name = $m[1];
		$type = misc::is($m[2], "S");

		if ($name) {
			if (array_key_exists($name, $this->ph_params)) {
				$value = $this->ph_params[$name];
			} elseif ($name == "now") {
				$value = true;
				$type = "NOW";
			} else {
				throw new E_Pdo_Exception("Unknown placeholder name: \"".$name."\"");
			}
		}

		if (is_null($value))
			$value = "NULL";
		else {
			switch ($type) {
				case "S":
					$value = $this->oPdo->quote($value, PDO::PARAM_STR);
					break;
				case "D":
					$value = intval($value);
					break;
				case "F":
					$value = str_replace(",", ".", floatval($value));
					break;
				case "B":
					$value = ($value ? "true" : "false");
					break;
				case "N":
					$value = empty($value) ? "NULL" : intval($value);
					break;
				case "L":
					if (!is_array($value))
						throw new E_Pdo_Exception("Value for placeholder '".$m[0]." must be array.");
					$value = array_map(array($this->oPdo, "quote"), $value);
					$value = implode(", ", $value);
					break;
				case "A":
					if (!is_array($value)) {
						throw new E_Pdo_Exception("Value for placeholder '".$m[0]." must be array.");
					} elseif (empty($value)) {
						$value = "NULL";
					} else {
						$value = "ARRAY[".implode(", ", $value)."]";
					}
					break;
				case "T":
					$this->tables[$value] = 1;
					$value = $this->oPdo->tquote($value);
					break;
				case "V":
					$value = $this->oPdo->quote($this->oPdo->getTimestamp($value), PDO::PARAM_STR);
					break;
				case "O":
					if (!is_array($value)) {
						$value	= array($value => "");
					}
					$parts = array();
					foreach ($value as $k=>$v)
						$parts[] = $k." ".(preg_match("/desc/i", trim($v)) ? "DESC" : "ASC");
					$value = implode(", ", $parts);
					break;
				case "NOW":
					$value = "NOW()";
					break;
				default:
					throw new E_Pdo_Exception("Unknown placeholder type identifier: ".$type);
			}
		}

		return $value;
	}
}
?>