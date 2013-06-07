<?php

class Controller
{
    public function __construct()
    {
        $r = array(
            'Auth' => U_GAuth::check(),
        );

        Response()->assign($r);
    }
}