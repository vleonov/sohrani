<?php

class registry {

    private $store = array(array());

    static private $thisInstance = null;

    public function __construct() {

    }

    /**
     * Получение экземпляра объекта
     * @return registry
     */
    static public function getInstance() {
        if(self::$thisInstance == null)
        {
            self::$thisInstance = new registry();
        }
        return self::$thisInstance;
    }

    /** standard */

    public function register($label, $object) {
        if(!isset($this->store[0][$label]))
        {
            $this->store[0][$label] = $object;
        }
    }

    public function unregister($label) {
        if(isset($this->store[0][$label]))
        {
            unset($this->store[0][$label]);
        }
    }

    public function get($label) {
        if(isset($this->store[0][$label]))
        {
            return $this->store[0][$label];
        }
        return false;
    }

    public function has($label) {
        if(isset($this->store[0][$label]))
        {
            return true;
        }
        return false;
    }

    // Test friendly isolation methods for original data

    public function backup() {
        // move $store[0] to $store[1]
        array_unshift($this->store, array());
    }

    public function restore() {
        // remove $store[0], restore $store[1] to it pre-backup() position
        array_shift($this->store);
    }

    public function dump() {
	var_dump($this->store);
    }

} // end class registry

?>