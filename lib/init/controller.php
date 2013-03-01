<?php
/**
 * Контроллер
 */
abstract class controller {

	private $params = null;

	public function __construct(&$r) {

	}

	public function params($name=false) {
		if ($name) {
			$result = isset($this->params["info"]["matches"][$name]) ? $this->params["info"]["matches"][$name] : false;
		} else {
			$result = $this->params;
		}
		return $result;

	}

	public function setParams($params) {
		$this->params =  $params;
	}

	/**
	 * Получение условия выборки
	 * @param string $name Название условия
	 * @param string $type Тип условия (int|text|date)
	 * @param string $source Источник данных (get|post)
	 * @param string $group Имя группы критериев
	 * @return mixed Значение условия
	 */
	protected function getCriteria( $name, $type, $source = 'get', $group = 'default' ) {

	    // Получение значения
	    $data = ( 'get' === $source ) ? $_GET : $_POST;
	    $value = misc::is( $data[ $name ], null );
	    $value = trim($value);

	    // Проверка корректности данных
	    $is_valid = false;
	    if( 'int' === $type )
	        $is_valid = is_numeric( $value );
	    elseif( 'text' === $type )
	        $is_valid = !empty( $value );
	    elseif( 'date' === $type )
	        $is_valid = (bool)preg_match("/\d{2}\.\d{2}\.\d{4}/", $value);

        // Обработка значения
	    if( 'get' === $source ) {
	        if( $is_valid && 'default' !== $value) {
    			$_SESSION[ "criteria_{$group}" ][$name] = $value;
    		} elseif( 'default' === $value ) {
    			$_SESSION[ "criteria_{$group}" ][$name] = null;
    		}
	    } else {
	        if( $is_valid ) {
	            if( 'default' !== $value )
    			    $_SESSION[ "criteria_{$group}" ][$name] = $value;
    		} elseif( isset( $_POST['submit'] ) ) {
    			$_SESSION[ "criteria_{$group}" ][$name] = null;
    		}
	    }

		$value = misc::is( $_SESSION[ "criteria_{$group}" ][$name], null );

		return $value;
	}

	/**
	 * Удаление условия выборки
	 * @param string $group Имя группы критериев
	 * @param string $name Имя критерия
	 */
	protected function dropCriteria( $group, $name=null ) {
	    if( !is_null( $name ) )
	        unset( $_SESSION[ "criteria_{$group}" ][$name] );
	    else
	        unset( $_SESSION[ "criteria_{$group}" ] );
	}

} // end class controller

?>