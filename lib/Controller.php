<?php

class Controller
{
    public function __construct()
    {
	$host = U_Url::host();
	$baseHref = '//' . $host . U_Misc::is(Config()->base[$host], '') . '/';
	
        $r = array(
            'Auth' => U_GAuth::check(),
            'BaseHref' => $baseHref,
        );

        Response()->assign($r);
    }
}