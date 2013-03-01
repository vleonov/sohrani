<?php
// DSN ��� ��� ������
define ('DSN', 'mysql:host=localhost;dbname=sohrani;user=root;password=');

define ("ROOT_URL", "http://".$_SERVER["HTTP_HOST"]."/");

define ("MAIL_FROM", "devnull@devnull.ru");

// ������� ��������� ����
ini_set('date.timezone','Asia/Novosibirsk');

// ������� �� html-�����
define("COMPRESS", false);

define ('PHP_COMMAND', 'php');
define('ROBOSCRIPT', 			PHP_COMMAND . ' -f ' . PROJECT_BINDIR . 'robot.php');


?>