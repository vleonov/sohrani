<?php

class Database {

    /**
     * @var PDO
     */
    protected $_connection;

    /**
     * @var Database
     */
    static protected $_instance;

    protected function __construct($dsn, $user)
    {
        $this->_connection = new PDO($dsn, $user);
        $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->_connection->exec('SET NAMES "UTF8"');
    }

    public static function get()
    {
        if (!self::$_instance) {
            self::$_instance = new self('mysql:host=localhost;dbname=sohrani', 'root');
        }

        return self::$_instance;
    }

    public function escape($value)
    {
        return $this->_connection->quote($value);
    }

    public function exec($sql)
    {
        return $this->_connection->exec($sql);
    }

    public function query($sql)
    {
        return $this->_connection->query($sql);
    }

    public function begin()
    {
        return $this->_connection->beginTransaction();
    }

    public function commit()
    {
        return $this->_connection->commit();
    }

    public function rollback()
    {
        return $this->_connection->rollBack();
    }

    public function getLastError()
    {
        return $this->_connection->errorInfo();
    }

    public function getLastId()
    {
        return $this->_connection->lastInsertId();
    }

    public function castValue($v)
    {

        if (is_null($v)) {
            $v = 'NULL';
        } elseif (is_array($v)) {
            $v = $this->arrayEncode($v);
        } else {
            switch (gettype($v)) {
                case 'integer':
                case 'double':
                    break;
                case 'boolean':
                    $v = $v ? 'TRUE' : 'FALSE';
                    break;
                case 'string':
                    $v = $this->_connection->quote($v);
                    break;
            }
        }

        return $v;
    }

    /**
     * Преобразовать массив pg в массив php
     * @param $value
     * @return array
     */
    public function arrayDecode($value)
    {
        // предполагаем, что $val содержит корректный массив из постгреса
        // предполагаем, что разделитель элементов - запятая

        // обрезаем первый и последний символ (фигурные скобки)
        // просто для удобства
        $value =  mb_substr($value, 1, mb_strlen($value) - 2);

        // главная регулярка
        $re = '/'
            // Элемент массива. Может быть обрамлён кавычками!
            . '(?P<elem>'
            // либо строка без кавычек
            . '([^{},"\\\\\\s]+)'
            . '|'
            // либо строка в кавычках
            . '("(.*?)'
            // кавычка, которой не предшествует нечетное
            // количество бэкслешей, т.е. не экранированная
            . '(?<!\\\\)(?:\\\\\\\\)*"'
            . ')' // кончился шаблон строки в кавычках
            . ')' // кончился шаблон элемента
            // за элементом либо запятая с пробелами, либо конец строки
            . '(?=\s*(,\s*)|$)'
            .'/ux';

        $result = array();
        preg_match_all($re, $value, $matches);

        if (isset($matches['elem'])) {
            foreach ($matches['elem'] as $elem) {
                // строка NULL без кавычек - это и есть null
                if ($elem == 'NULL') {
                    $elem = null;
                } else {
                    $first = mb_substr($elem, 0, 1);
                    if ($first == '"') {
                        $elem = mb_substr($elem, 1, mb_strlen($elem) - 2);
                    }
                }
                if (is_numeric($elem)) {
                    $result[] = floatval($elem);
                } else {
                    $result[] = stripcslashes($elem);
                }
            }
        }

        return $result;
    }

    /**
     * Преобразовать массив php в массив pg
     * @param $arr
     * @return string
     */
    public function arrayEncode($arr)
    {
        if (empty($arr)) {
            return "'{}'";
        }

        foreach ($arr as $i=>$v) {
            $arr[$i] = '"' . addcslashes($v, '"\\') . '"';
        }

        return  $this->escape("{" . join(',', $arr) . "}");
    }
}