<?php

define ('SMARTYTEMPLATE_DIR' , dirname(__FILE__));

include 'Smarty.class.php';

class smarty_common extends Smarty {

	function __construct($params='') {
		parent::__construct($params);
	    
		$this->template_dir = PROJECT_TEMPLATE_SDIR;
		$this->compile_dir = PROJECT_TEMPLATE_CDIR;
		$this->error_reporting = E_ALL;
		$this->request_use_auto_globals = false;
//		$this->force_compile = true; 
//		$this->compile_check = false; 
//		$this->caching = false;

		$this->register_modifier("highlight", array($this, "modifierHighlight"));
		$this->register_modifier("plural", array($this, "modifierPlural"));
		$this->register_modifier("beauty_number", array($this, "modifierBeautyNumber"));
	}	    
     
	public function getInstance() {
		return $this;
	}        
	public function modifierPlural($n, $form1, $form2, $form5) {
		$n = abs($n) % 100;
	    	$n1 = $n % 10;
    		if ($n > 10 && $n < 20) return $form5;
	    	if ($n1 > 1 && $n1 < 5) return $form2;
    		if ($n1 == 1) return $form1;
	    	return $form5;
	}


	function modifierHighlight ($text, $search) {

		static $_search_phrases;

		$trans = array();

		if (!$_terms = (array)$_search_phrases[$search]) {		
			preg_match_all( '/"(.*?)"/', $search, $_quotes);
			$_terms = array_merge((array)$_quotes[1], explode(' ', preg_replace( '/".*?"/', ' ', $search )));
			$_search_phrases[$search] = $_terms;
		}
		foreach (array_unique($_terms) as $val) {
			if (!$val = trim(str_replace('"', '', $val))) continue;
				if (preg_match_all('!' . preg_quote($val, '!') . '!i', $text, $matches, PREG_PATTERN_ORDER)) {
					foreach ($matches[0] as $match) {
						$trans[$match] = '<span class="highlight">' . $match . '</span>';
					}
				}

		}
		return strtr($text, $trans);

	}

	/**
	 * красиво выводит цисла
	 *
	 * @param  string        $number             число
	 * @param  string        $delimiter          разделитель
	 * @param  string        $point              разделитель целой части от дробной
	 * @param  integer       $precision          точность округлени€
	 * @return string                            результат разбиени€
	 */
	public function modifierBeautyNumber($number, $delimiter = "&nbsp;", $point = ".", $precision = 2) {
		$number = trim($number);
		$number = round($number, $precision);
		if (preg_match("/^([\d]+)([\.\,]([0-9]+)|)$/i", $number, $r)) {
			return $this->split_formatter($r[1], array(3, 3, 3, 3, 3, 3, 3), $delimiter).(!empty($r[3]) ? $point.$r[3] : "");
		} else {
			return $number;
		}
	}


	/**
	 * разбиение символов на группы
	 *
	 * @param  string        $what               строка, требующа€ разбиени€
	 * @param  array         $groups             числовой массив, представл€ющий группы символов
	 * @param  string        $delimiter          разделитель
	 * @return string                            результат разбиени€
	 */
	protected function split_formatter($what, $groups = array(), $delimiter = " ") {
		if (empty($groups)) {
			return $what;
		}
		if (empty($what) && $what != 0) {
			return "";
		}
		$len = strlen($what);
		if ($len <= $groups[0]) {
			return $what;
		}

		$cursor = 0;
		$result = array();

		for($i=0, $ci=sizeof($groups); ($i<$ci || $cursor>$len); $i++) {
			$result[] = substr($what, $len-$cursor-$groups[$i], $groups[$i]);
			if ($cursor+$groups[$i] >= $len) {
				$cursor = $len;
				break;
			}
			if ($i>10) {
				throw new E_Exception("всЄ сломалось!");
			}
			$cursor += $groups[$i];
			if (isset($groups[$i+1]) && $cursor+$groups[$i+1] > $len) {
				$groups[$i+1] = $len - $cursor;
			}
		}

		if ($cursor < $len) {
			$result[] = substr($what, 0, $len-$cursor);
		}

//		debug::v($result);
		return implode($delimiter, array_reverse($result));
	}

}
?>