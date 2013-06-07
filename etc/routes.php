<?php

return array(

    '/' => array(
        'Main',
    ),

    '/login/' => array(
        'Auth' => 'login',
    ),
    '/logout/' => array(
        'Auth' => 'logout',
    ),
    '/login/gauth/' => array(
        'Auth' => 'gauthCallback',
    ),

    '/ajax/card/read/(\d+)/' => array(
        'Card' => 'read',
    ),
    '/ajax/card/del/(\d+)/' => array(
        'Card' => 'delete',
    ),
    '/ajax/card/save/' => array(
        'Card' => 'save',
    ),
);