<?php

/**
 * Абстрактный класс модуля
 */

abstract class module {

	/**
	 * Список объектов, отражающихся этим модулем
	 */
	protected $objects;

	/**
	 * Префикс, для добавления к названию объекта
	 */
	protected $prefix;

	final public function getInstance() {
		return $this;
	}

	/**
	 * Получение объекта по id
	 */
	abstract public function get($object, $id);

	/**
	 * Запись объекта (обновление существующего при наличии id или создание нового в противном случае)
	 */
	abstract public function set($object, $data, $id = null);

	/**
	 * Удаление объекта по id
	 */
	abstract public function delete($object, $id);

	public function checkObject($object) {
		if (!key_exists($object, $this->objects)) {
			throw new Exception("No such object '".$object."' of class ".get_class($this));
		}
		return true;
	}

}

?>