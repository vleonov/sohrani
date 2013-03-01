<?php

define ('MODEL', 0);
define ('HELPER', 1);

function load ( $plugs, $is_once = false ) {

	static $loaded = array ( ) ;

	$plugins = (strstr($plugs, ',') !== false) ? explode ( ',', $plugs ) : array($plugs);

	$result = array ( ) ;

	foreach ( $plugins as $pluginName ) {

		if (($strpos = strpos($pluginName, "/")) !== false) {
			$filename = substr($pluginName, 0, $strpos) . '.plugin.php';
		} else {
			$filename = $pluginName . '.plugin.php';
		}
		$filename = strtolower($filename);

		$exists = false;

		if (file_exists(PROJECT_HELPERDIR . $filename)) {
			$filename = PROJECT_HELPERDIR . $filename;
			$exists = true;
		} else if (file_exists(PROJECT_SYSTEMDIR . $filename)){
			$filename = PROJECT_SYSTEMDIR . $filename;
			$exists = true;
		}

		if (!$exists) {
			trigger_error( 'Cannot load plugin ' . $pluginName, E_USER_ERROR ) ;
		} else {
			if (! isset ( $loaded [ $pluginName ] )) {
				require $filename;
				$loaded [ $pluginName ] = $filename ;
			}

			$registry = registry::getInstance();
			$result [ $pluginName ] = $registry->get ( $pluginName ) ;
		}
	}

	if ($is_once && sizeof($result) == 1) {
		$result = $result[$pluginName]->getInstance();
	}

	return $result ;

} // end load()

function __autoload ( $className ) {
	static $loaded = array ( ) ;

	$filename = strtr ( $className, array ( '_' => DIRECTORY_SEPARATOR ) ) . '.php';

	if (file_exists(PROJECT_CONTROLLERDIR . $filename)) {
		$filename = PROJECT_CONTROLLERDIR . $filename;
    		$exists = true;
	} else if (file_exists(PROJECT_HELPERDIR . $filename)){
		$filename = PROJECT_HELPERDIR . $filename;
		$exists = true;
	} else if (file_exists(PROJECT_SYSTEMDIR . $filename)){
		$filename = PROJECT_SYSTEMDIR . $filename;
		$exists = true;
	} else if (file_exists(PROJECT_CMFACTIONSDIR."common/".$filename)){
		$filename = PROJECT_CMFACTIONSDIR."common/".$filename;
		$exists = true;
	} else if (file_exists(PROJECT_SYSTEMROBOTDIR.$filename)){
		$filename = PROJECT_SYSTEMROBOTDIR.$filename;
		$exists = true;
	} else {
		$exists = false;
	}

	if (!$exists) {
	    if (strpos($filename, '_')) {
		trigger_error ( 'Cannot create class <strong>' . $className . '</strong>', E_USER_ERROR ) ;
	    }
	} else {

		if (! isset ( $loaded [ $className ] )) {

			require $filename;

			if (! class_exists ( $className )) {
				trigger_error ( 'Cannot find class ' . $className . ' in ' . $filename, E_USER_ERROR ) ;
			}

			$loaded [ $className ] = $filename ;
		}
	}

} // end __autoload()

function useModel($name) {

	static $loaded = array();

	if (!isset($loaded[$name])) {

		$file = getNameSpace($name, MODEL);

		if (file_exists($file)) {
			require $file;
			$loaded[$name] = $file;
		}
	}

} // end useModel()

function getNameSpace($name, $type = MODEL) {
	switch($type) {
		case MODEL:
			$service = ( defined('SERVICE') ? ucfirst(SERVICE) . DIRECTORY_SEPARATOR : '' );
			if (!IS_SHELL) {
				$filename = PROJECT_CONTROLLERDIR . $service . $name . '.php';
			} else {
				$filename = PROJECT_ROBOTDIR . $name . '.php';
			}
		break;

		case HELPER:
			$filename = PROJECT_HELPERDIR . $name . DIRECTORY_SEPARATOR . $name . '.php';
		break;

		default:
			$filename = PROJECT_DIR . $name . '.php';
		break;
	}
	return $filename;

} // end getNameSpace()

?>