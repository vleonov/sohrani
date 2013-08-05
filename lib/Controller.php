<?php

class Controller
{
    public function __construct()
    {
	$host = U_Url::host();
	$configBase = Config()->base;
	$baseHref = '//' . $host . U_Misc::is($configBase[$host], '') . '/';
	
        $r = array(
            'Auth' => U_GAuth::check(),
            'BaseHref' => $baseHref,
        );

        Response()->assign($r);
    }
}