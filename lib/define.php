<?php
/**
 * Констнаты проекта
 */

// Разделитель директорий (прямой или обратный слеш)
define('DS', DIRECTORY_SEPARATOR);

// Пути проекта
define('PROJECT_DIR',			ROOTDIR);

define('PROJECT_BINDIR',        ROOTDIR . 'bin' . DS);
define('PROJECT_HTDOCSDIR',     ROOTDIR .  DS);
define('PROJECT_DATADIR',     ROOTDIR .  DS . 'www' . DS . 'data' . DS);

define('PROJECT_ETCDIR',		ROOTDIR . 'etc' . DS);
define('PROJECT_LIBDIR',		ROOTDIR	. 'lib' . DS);
define('PROJECT_LIBSDIR',		ROOTDIR	. 'lib' . DS . 'libs' . DS);

define('PROJECT_CMFDIR',		PROJECT_LIBDIR	. 'init' . DS);
define('PROJECT_SYSTEMDIR',		PROJECT_LIBDIR	. 'modules' . DS);
define('PROJECT_CMFACTIONSDIR', PROJECT_LIBDIR	. 'actions' . DS);
define('PROJECT_SYSTEMROBOTDIR', PROJECT_LIBDIR . 'actions/_robots' . DS);

define('PROJECT_SITEDIR',		ROOTDIR	. 'usr' . DS );

define('PROJECT_CONTROLLERDIR', PROJECT_SITEDIR . 'actions' . DS);
define('PROJECT_ROBOTDIR' , 	PROJECT_SITEDIR . 'actions/_robots' . DS);
define('PROJECT_HELPERDIR' , 	PROJECT_SITEDIR . 'modules' . DS);
define('PROJECT_SERVICEDIR' , 	PROJECT_SITEDIR . 'services' . DS);

define('PROJECT_VARDIR', 		PROJECT_DIR 	. 'var' . DS);
define("PROJECT_VAR_TMP",       PROJECT_VARDIR."tmp".DS);

define('PROJECT_TEMPLATE_SDIR', 	PROJECT_SITEDIR	. 'views' . DS);
define('PROJECT_TEMPLATE_CMFSDIR', 	PROJECT_LIBDIR	. 'views' . DS);
define('PROJECT_TEMPLATE_CDIR', 	PROJECT_VARDIR	. 'cache' . DS);

define('PROJECT_LOGDIR', 		PROJECT_VARDIR	. 'log' . DS);

define('ERRLOG', 		        PROJECT_LOGDIR	. 'error.log');
define('TRACELOG', 			    PROJECT_LOGDIR	. 'trace.log');

// Скрипт для запуска роботов

// Установка пути поиска файлов
$path = array(
    PROJECT_DIR ,
    PROJECT_CONTROLLERDIR,
    PROJECT_CMFDIR,
	PROJECT_SYSTEMDIR,
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(';', $path));

?>