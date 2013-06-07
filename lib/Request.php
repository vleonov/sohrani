<?php

/**
 * Shortcut for Request::getInstance()
 * @return Request
 */
function Request()
{
    return Request::getInstance();
}

class Request
{
    static $_instance;

    protected $_args = array();

    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (!is_null(self::$_instance)) {
            return self::$_instance;
        }

        self::$_instance = new self();

        return self::$_instance;
    }

    public function setArgs(array $args)
    {
        $this->_args = $args;

        return $this;
    }

    public function args($index = null, $default = null)
    {
        if (is_null($index)) {
            return $this->_args;
        } else {
            return U_Misc::is($this->_args[$index], $default);
        }
    }

    public function get($key, $default = null)
    {
        return U_Misc::is($_GET[$key], $default);
    }

    public function isPost()
    {
        return U_Misc::is($_SERVER['REQUEST_METHOD']) == 'POST';
    }

    public function post($key, $default = null)
    {
        return U_Misc::is($_POST[$key], $default);
    }

    public function cookie($key, $default = null)
    {
        return U_Misc::is($_COOKIE[$key], $default);
    }

    public function __get($key)
    {
        return U_Misc::is($_REQUEST[$key]);
    }

    public function backUrl()
    {
        if ($this->backUrl) {
            return $this->backUrl;
        } elseif (!empty($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return PROJECT_HOST;
        }
    }
}