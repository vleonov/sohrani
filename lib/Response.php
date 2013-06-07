<?php

/**
 * Shortcut for Response::getInstance()
 * @return Response
 */
function Response()
{
    return Response::getInstance();
}

/**
 * Shortcut for Response::getInstance()->ajax()
 * @return Response
 */
function AjaxResponse()
{
    return Response::getInstance()->ajax();
}

class Response
{
    static $_instance;

    protected $_code = 200;
    protected $_headers = array();
    protected $_data = array();
    protected $_template;
    protected $_isAjax = false;

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

    public function assign($r, $value = null)
    {
        if (is_array($r)) {
            $this->_data = $r + $this->_data;
        } else {
            $this->_data[$r] = $value;
        }

        return $this;
    }

    public function fetch($template)
    {
        $this->_template = $template;

        return $this;
    }

    public function ajax()
    {
        $this->_headers['Content-Type'] = 'application/json';
        $this->_isAjax = true;

        return $this;
    }

    public function redirect($url)
    {
        $url = U_Url::host($url) ? $url : PROJECT_HOST . $url;
        $this->_headers['Location'] = $url;

        return $this;
    }

    public function error404()
    {
        $this->_code = 404;
        $this->_template = '404.tpl';

        return $this;
    }

    public function setCookie($key, $value, $expire = null, $path = null)
    {
        $expire = time() + ($expire ? $expire : 365 * 24 * 60 * 60);
        $path = $path ? $path : '/';

        setcookie($key, $value, $expire, $path, PROJECT_DOMAIN);

        return $this;
    }

    public function delCookie($key)
    {
        setcookie($key, '', -1, '/', PROJECT_DOMAIN);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_setHeaders()->_render();
    }

    protected function _setHeaders()
    {
        switch ($this->_code) {
            case 404:
                header("HTTP/1.0 404 Not Found");
                break;
        }

        foreach ($this->_headers as $key=>$value) {
            header($key . ': ' . $value);
        }

        return $this;
    }

    protected function _render()
    {
        if ($this->_template) {
            $oView = new View();
            return $oView->assign($this->_data)->fetch($this->_template);
        } elseif ($this->_isAjax) {
            return json_encode($this->_data);
        } else {
            return '';
        }
    }
}