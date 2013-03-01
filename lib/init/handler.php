<?php

class handler {

	function __construct () {
		$this->set () ;
	}

	/**
	 * Установка обработчиков
	 */
	public function set () {
		set_error_handler ( array ( $this , 'error_handler' ) ) ;
		set_exception_handler ( array ( $this , 'exception_handler' ) ) ;
	}

	/**
	 * Обработчик ошибок
	 */
	static public function error_handler ( $type , $msg , $file = __FILE__ ,
		$line = __LINE__, $trace = array() ) {
		$error_reporting = ini_get("error_reporting");

		if ($error_reporting & $type) {
		    $umask = umask(0000);
			$head = handler::_head($type, $msg, $file, $line).PHP_EOL;

			$err = $head.$msg.PHP_EOL;
			error_log ($err, 3, ERRLOG);

			$trace = misc::is($trace["e"], $trace);
			//if( is_array( $trace ) )
			//    $trace = array_slice( $trace, 0, 5 );
			$trace = $head.print_r($trace, true).PHP_EOL;
			error_log ($trace, 3, TRACELOG);

			umask( $umask );
		}
	}

	/**
	 * Обработчик исключений
	 */
	static public function exception_handler ( Exception $ex ) {

		// Получение описания ошибки
		$error_message = '[' . get_class( $ex ) . '] ';
		$detail = misc::is($ex->error['detail'], null);
		if( $detail ) {
			if( $detail instanceof HessianException )
				$error_message .= $detail->getMessage();
			else
				$error_message .= misc::is( $detail['faultString'],
				    $detail['detailMessage'] );
		} else {
			$error_message .= $ex->getMessage();
		}

		handler::error_handler(
			$ex->getCode(), $error_message, $ex->getFile(), $ex->getLine(), $ex->getTrace()
		);
	}

	static function _head ( $errno , $errmsg , $file , $line) {

		static $errors = array (
			E_ERROR			=> 'Error' ,
			E_WARNING		=> 'Warning' ,
			E_PARSE			=> 'Parsing error' ,
			E_NOTICE		=> 'Notice' ,
			E_CORE_ERROR		=> 'Core error' ,
			E_CORE_WARNING		=> 'Core warning' ,
			E_COMPILE_ERROR 	=> 'Compile error' ,
			E_COMPILE_WARNING	=> 'Compile warning' ,
			E_USER_ERROR		=> 'User error' ,
			E_USER_WARNING		=> 'User warning' ,
			E_USER_NOTICE		=> 'User notice'
		) ;

	    $err = isset($errors[$errno]) ? $errors[$errno] : 'Unknown error' ;

		$errmsg = strip_tags($errmsg);

		$result = str_pad(misc::is($_SERVER["REQUEST_URI"], "SHELL"), 100, "=", STR_PAD_BOTH).PHP_EOL;

		$result .= "[".date('d/m/Y H:i:s')."] ".$err." in ".$file." on ".$line;

		return $result ;
	}

} // end class handler

$eh = new handler ;

?>