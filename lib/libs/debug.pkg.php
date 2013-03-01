<?php

class debug {

	static $timer = false;

	static function d ( $value , $method = "dump" ) {
		ob_start () ;

		echo '<hr/><pre>' ;

		switch ( $method) {
			case 'export' :
				{
					var_export ( $value ) ;
				}
			break ;

			case 'print' :
				{
					print_r ( $value ) ;
				}
			break ;

			default :
			case 'dump' :
				{
					var_dump ( $value ) ;
				}
			break ;
		}

		echo '</pre>' . PHP_EOL ;

		$result = ob_get_clean();

		return $result ;

	} // end p()


	static function p ( $value ) {
		echo debug::d ( $value, 'print' ) ;
	} // end p()


	static function e ( $value ) {
		echo debug::d ( $value, 'export' ) ;
	} // end p()

	static function log($msg, $filename = 'app.log') {

		$file = PROJECT_LOGDIR . $filename;

		if( is_array($msg) )
		    $msg = print_r($msg, true);
		$info = date("Y-m-d H:i:s") . PHP_EOL . $msg . PHP_EOL . PHP_EOL;

		$fh = fopen($file, "a");
		if ($fh) {
			fwrite($fh, $info);
		}
		fclose($fh);

	} // end debug()

	static function gettimer() {
		list($usec, $sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec);

	} // end gettimer()

	static function timer() {

		$result = false;

		if (!self::$timer) {
			self::$timer = debug::gettimer();
		} else {
			$result = debug::gettimer() - self::$timer;
			self::$timer = false;
		}
		return $result;

	} // end timer()
}

?>