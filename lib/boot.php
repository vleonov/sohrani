<?php
/**
 * Загрузочный (bootstrap) файл
 *
 * Точка входа в систему
 */

// Установка локали
setlocale(LC_ALL, "ru_RU.UTF-8");
setlocale(LC_NUMERIC, "C");

// Подключение необходимых библиотек
define('ROOTDIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
require ROOTDIR . 'lib/common.php';

// Установка параметров сессии и кук
$session_name = "CCSID";
$cookie_path = "/";
$timeout = 60 * 24 * 14;
$cookie_timeout = 60 * $timeout;
$garbage_timeout = $cookie_timeout + 600;
session_set_cookie_params( $cookie_timeout, $cookie_path );
session_cache_expire( $timeout );
ini_set( 'session.gc_maxlifetime', $garbage_timeout );
session_name($session_name);
if (isset($_COOKIE[$session_name])) {
	setcookie($session_name, $_COOKIE[$session_name], time() + $cookie_timeout, "/");
}

// Запуск ядра
try {

	if (IS_SHELL) {
		$url = misc::is($argv[1]);
	} else {
		$url = null;
	}

	$app = new core($structure);
    $app->process($url);

} catch (Exception $e) {
    die($e->getMessage());
}

?>