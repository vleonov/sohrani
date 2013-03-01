<?php
/**
 * Подключение библиотек
 */

// Константы проекта
require_once 'define.php';

// Конфигурация проекта
require_once PROJECT_ETCDIR . 'config.php';

// Утилиты для разработки
require_once PROJECT_CMFDIR . 'sdk.php';

// Обработка ошибок
error_reporting(E_ALL);
if (defined("DEBUG_MODE") && DEBUG_MODE) {
    ini_set('display_errors', 'on');
    ini_set('log_errors', 'off');
    ini_set('display_startup_errors', 'on');
    ini_set('html_errors', 'off');
} else {
    ini_set('display_errors', 'off');
    ini_set('log_errors', 'on');
    ini_set('display_startup_errors', 'off');
    ini_set('html_errors', 'off');
}

// Подключение файла структуры
$structure_file = (!IS_SHELL) ? 'structure.php' : 'structure.robot.php';
require_once PROJECT_ETCDIR . $structure_file;
registry::getInstance()->register('structure', $structure);

if( !IS_SHELL ) {
    $url = misc::wo_args(isset($_SERVER [ 'REQUEST_URI' ]) ? strval($_SERVER [ 'REQUEST_URI' ]) : '');
    $url = split("/", $url);
    $service = isset($url[1]) ? $url[1] : null;
    if ($service && file_exists(PROJECT_ETCDIR."structure.".$service.".php")) {
    	require_once PROJECT_ETCDIR . "structure.".$service.".php";
    	define("SERVICE", $service);
    }
}

// Подключения ядра проекта
require_once PROJECT_CMFDIR . 'core.php';
?>