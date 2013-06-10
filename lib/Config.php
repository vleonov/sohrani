<?php

/**
 * Shortcut for Config::getInstance()
 * @return Config
 */
function Config()
{
    return Config::getInstance();
}

class Config
{
    static $_instance;

    private $_data = array();

    private function __construct($filename)
    {
        $this->_data = include $filename;
    }

    public static function getInstance()
    {
        if (!is_null(self::$_instance)) {
            return self::$_instance;
        }

        $filename = ETC_DIR . '/config.php';

        if (!file_exists($filename)) {
            throw new Exception("Config file doesn't exist: " . $filename);
        }

        self::$_instance = new self($filename);

        return self::$_instance;
    }

    public function __get($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        } else {
            return array();
        }
    }

}