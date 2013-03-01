<?php

class misc {
	static public function is(&$value, $def=false) {
		return (isset($value) ? $value : $def);
	}

	public static function array_get_col($array, $col) {
		$res = array();
		if (!empty($array)) foreach ($array as $k=>$v) $res[] = $v[$col];
		return $res;
	}

	public static function check_dir($dir, $mode = 0777) {
		if (!is_dir($dir)) {
			$old = umask(0);
			$res = mkdir($dir, $mode, 1);
			if (!$res) $res = @mkdir($dir, $mode, 1);
			if ($res) chmod($dir, $mode);
			umask($old);
			if (!$res)
				trigger_error("Can't make dir: ".$dir);
		}
	}

	public static function iconv($data, $in_charset = "UTF-8", $out_charset = "WINDOWS-1251//IGNORE") {
		if (is_array($data)) {
			foreach ($data as $k=>$v) {
				$data[$k] = misc::iconv($v, $in_charset, $out_charset);
			}
		} else/*if (preg_match("/([а-я])/i", $data, $r))*/ {
			for ($i = 0, $ci = strlen($data); $i<$ci; $i++) {
				if (ord($data[$i]) < 32) {
					//Binary data
					return $data;
				}
			}
			$data = @iconv($in_charset, $out_charset, $data);
		}

		return $data;
	}

	public static function exec($cmd, &$pipes) {
		$pipes = array();
		$pipes_desc = array(
			//0 => array("pipe", "r"), // stdin
			1 => array("pipe", "w"), // stdout
			2 => array("pipe", "w") // stderr
		);

		$res = array(1=>"", 2=>"");
		$return_value = false;
		$process = proc_open($cmd, $pipes_desc, $pipes);
		// set for non-blocking pipes
		//stream_set_blocking($pipes[1], 0);
		//stream_set_blocking($pipes[2], 0);
		if (is_resource($process)) {
			for ($i=1; $i<3; $i++) {
				while(!feof($pipes[$i]))
					$res[$i] .= fgets($pipes[$i], 4096);
				fclose($pipes[$i]);
			}
			$return_value = proc_close($process);
		}
		$pipes = $res;

		return $return_value;
	}

	public static function parse_adress($address) {
		$address = split(",", $address);
		$res = array(
			"index" => "",
			"region" => "",
			"city" => "",
			"street" => "",
			"number" => "",
		);
		end($res);
		for ($i = sizeof($address)-1; $i>=0; $i--) {
			$key = key($res);
			$res[$key] = $address[$i];
			prev($res);
		}

		return $res;
	}

	public static function wo_args($url) {

		$url_wo_args = $url;

		if ( strpos ($url, '?') !== false ) {
			list($url_wo_args, $args) = explode("?", $url_wo_args);
		}

		$result = strtolower($url_wo_args);
		if (substr($result, -1, 1) !== '/') {
			$result .= '/';
		}
		if ($result[0] !== '/') {
			$result = "/".$result;
		}
		$result = str_replace("//", "/", $result);

		return $result;

	}


	//Преобразование десятичного числа в строку битов независимо от разрядности системы
	public static function dec2bin($dec) {
		$result = '';
		$shift = 0;

		if ($dec < 0) {
			$minus = true;                        // отрицательное
			$dec = -1 * ($dec+1);                   // делаем NOT, чтоб делить положительное число, но ставим флаг что отрицательное
		} else {
			$minus = false;
		}

		while ( pow(2, $shift) < $dec ) {        // стандартные наработки
			++$shift;
		}
		while ( 0 <= $shift ) {
			$pow = pow(2, $shift);
			if ( $pow <= $dec ) {
				$dec -= $pow;
				$result = $result . ($minus?'0':'1');   // если отрицательное число, то NOT
			} else {
				$result = $result . ($minus?'1':'0');
			}
			--$shift;
		}
		$result = str_pad($result, 64, ($minus?"1":"0"), STR_PAD_LEFT);

		return $result;
	}

	//Преобразование строки битов в десятичное число независимо от разрядности системы
	public static function bin2dec($c) {
		if ($c > "0000000000000000000000000000000001111111111111111111111111111111") {

			$c = str_pad($c, 64, "0", STR_PAD_LEFT);
			for ($i=63;$i>=0;$i--) {
				$c[$i]=((int)$c[$i]^1); //^1;
			}

			$c=base_convert(substr($c,32), 2, 10);
			$c=-1*($c+1);
		} else {
			$c=bindec($c);
			$c = sprintf("%d", $c);
		}


		return $c;
	}

	public static function readdir($dirname, $is_only_files = false) {
		$dd = opendir($dirname);
		$res = array();
		if ($dd) {
			while ($file = readdir($dd)) {
				if ($file != "." && $file != "..") {
					if (!$is_only_files || is_file($dirname."/".$file)) {
						$res[] = $file;
					}
				}
			}
		} else {
			trigger_error("Cant't read dir ".$dirname);
		}
		return $res;
	}

	public static function stripslashes($value) {
		if (is_array($value)) {
			foreach($value as $k=>$v)
				$value[$k] = misc::stripslashes($v);
		} elseif (is_string($value)) {
			$value = stripslashes($value);
		}
		return $value;
	}
}

?>