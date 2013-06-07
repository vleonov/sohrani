<?php

include dirname(__FILE__) . '/../lib/__init.php';

$oRouter = new Router();

$callback = $oRouter->proceed();
$oResponse = $callback ? call_user_func($callback) : Response()->error404();

echo $oResponse;

exit();

