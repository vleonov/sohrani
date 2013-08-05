<?php

class Router {

    protected $_uri;
    protected $_routes;
    protected $_matches = array();

    public function __construct($uri = null)
    {
        if (is_null($uri)) {
            $uri = U_Url::path();
        }
        
        $configBase = Config()->base;
        $base = U_Misc::is($configBase[U_Url::host()], '');
        $uri = trim(trim($uri), '/');

	$this->_uri = preg_replace('~^' . $base . '~', '', ($uri ? '/' . $uri . '/' : '/'));

        $this->_routes = include(ROOT_DIR . '/etc/routes.php');
    }

    public function proceed()
    {
        $result = null;
        foreach ($this->_routes as $reg=>$data) {
            if (!preg_match('~^' . $reg . '$~', $this->_uri, $matches)) {
                continue;
            }

            $item = each($data);

            if (is_int($item['key'])) {
                $method = 'main';
                $controller = $item['value'];
            } else {
                $method = $item['value'];
                $controller = $item['key'];
            }

            Request()->setArgs($matches);

            $controller = 'C_' . $controller;
            $oController = new $controller();
            $result = array($oController, $method);

            break;
        }

        return $result;
    }
}