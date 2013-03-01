<?php
// Объявление стандартных констант
define ( 'CONTROLLER', "controller" ) ;
define ( 'ACTION', "action") ;
define ( 'CRUMBNAME', "title") ;

// Текущий URL
if( !IS_SHELL ) {
	if ($_SERVER['REQUEST_URI'] != '/') {
    	define ("THIS_URL", ROOT_URL . trim(misc::wo_args($_SERVER['REQUEST_URI']), "/") . "/");
	} else {
		define ("THIS_URL", ROOT_URL);
	}
} else {
    define ("THIS_URL", false);
}

// Уровни оповещения пользователя
define("FLASH_NORM", 0);
define("FLASH_WARN", 1);
define("FLASH_ERROR", 2);

/**
 * Ядро движка
 */
class core {

	/**
	 * Структура доступных контроллеров
	 * @var array
	 */
	protected $structure ;

	/**
	 * Таймер для профилирования времени выполнения скрипта
	 * @var float
	 */
	protected $timer;

	/**
	 * Утилита для разбора структуры контроллеров
	 */
	private function _parse($match, $req) {
		$result = $match;
		foreach ($req as $k => $v) {
			$repl = "(?<" . strtr($k, array(":" => "")) . ">(" . $v . "))";
			$result = strtr($result, array($k => $repl));
		}
		return $result;

	}

	/**
	 * Конструктор
	 * @param $structure Структура доступных контроллеров
	 */
	function __construct ( $structure ) {
		$this->timer = microtime(true);

		$this->structure = array();

		foreach ($structure as $k => $v) {
			$key = $k;
			if (strstr($k, ":") !== false) {
				if (!empty($v["required"])) {
					$key = $this->_parse($k, $v["required"]);
				}
			}
			$this->structure[$key] = $v;

		}
		krsort($this->structure);
		$this->proceedIncomingData();

	} // end default constructor

	/**
	 * Обработка запроса
	 * @param string $url Запрошенный URL
	 */
	public function process ($url = null) {

		if (!$url) {
			$url = isset($_SERVER [ 'REQUEST_URI' ]) ? strval($_SERVER [ 'REQUEST_URI' ]) : '' ;
		}

		$url_wo_args = misc::wo_args($url);

		$filter = $this->_find ( $url ) ;

		$this->_call ( $filter, $url ) ;

	} // end process()


	/**
	 * Поиск запрошенного URL'a в списке доступных контроллеров
	 * @param string $url Запрошенный URL
	 * @return array Информация о контроллере
	 */
	public function _find ( $url , $is_ignore_errors = false ) {

		$url_wo_args = misc::wo_args($url);
		$found = $result = false;

		if (isset($this->structure[$url_wo_args])) {
			$result = $this->structure[$url_wo_args];
			$result['matches'] = array();
			$found = true;
		} else {
			foreach ($this->structure as $k => $v) {
				if (!preg_match( '~^' . $k . '$~', $url_wo_args . '', $matches)) {
					continue;
				} else {
					$result = $v;
					array_shift($matches);
					$result['matches'] = $matches;
					$found = true;
				}
			}
		}

		if (!$found && !$is_ignore_errors) {
			if (IS_SHELL) {
				throw new Exception("No such url '".$url."'");
			} else {
				core::page404();
			}
		}

		return $result ;

	} // end _find()


	/**
	 * Передача управления контроллеру
	 * @param array $info Информация о контроллере
	 * @param string $url Запрошенный URL
	 */
	private function _call ( $info , $url ) {

		$controller = $info [ CONTROLLER ] ;
		$action = $info [ ACTION ] ;
		$pagetitle= misc::is($info[CRUMBNAME], "");

		$name = $controller . DIRECTORY_SEPARATOR . $action ;

		$path = array ( ) ;

		$path [ 'model' ] = $this->name ( $name ) ;
		$path [ 'tmpl' ] = $this->name ( $name, true ) ;

		$data = array (
			'http_host' => (isset($_SERVER["HTTP_HOST"]) ? "http://".$_SERVER["HTTP_HOST"] : "/").ROOT_URL,
			'controller' => $controller,
			'action' => $action,
			'name' => $name,
			'url' => $url,
			'info' => $info,
			'pagetitle' => $pagetitle,
		) ;

		$this->_run ( $data ) ;

	} // end _call()


	/**
	 * Запуск контроллера
	 */
	private function _run ( $params ) {

		// Подключение контроллера
		useModel ( $params [ 'controller' ] ) ;
		if (! class_exists ( $params [ 'controller' ] )) {
			trigger_error ( '[' . get_class ( $this ) . '] Cannot find class declaration for ' . $params [ 'name' ], E_USER_ERROR ) ;
		}

		// Проверка наличия вызываемого метода у контроллера
		$methods = get_class_methods ( $params [ 'controller' ] ) ;
		$action = 'action' . ucfirst ( $params [ 'action' ] ) ;
		if (! in_array ( $action, $methods )) {
			trigger_error ( '[' . get_class ( $this ) . '] Cannot find action for ' . $params [ 'name' ], E_USER_ERROR ) ;
		}

		// Создание объекта контроллера
		$result = $params;
		$obj = new $params [ 'controller' ] ($result) ;
		$obj->setParams($params);

		// Вызов метода котроллера
		$callee = array ( $obj , $action );
		if (is_callable($callee)) {
			try {
				call_user_func ( $callee, &$result ) ;
			} catch (Exception $e) {
				trigger_error($e->getMessage());
				if (!IS_SHELL) {
					core::page503();
				} else {
					throw $e;
				}
			}
		}

		// Сборка шаблона
		if (!IS_SHELL) {
			$this->_render($params['name'], $result);
		} else {
			exit();
		}

	} // end _run()


	/**
	 * Сборка шаблона
	 */
	private function _render ($name, $data) {

		$logined = isset ( $_SESSION [ 'sid' ] ) ? $_SESSION [ 'sid' ] : false ;

		// Определение имени шаблона
		$path_tmpl = $this->name( $name,
			isset( $data['render'] ) ? $data['render'] : true ) ;

		// Проверка существования шаблона
		if( !file_exists( $path_tmpl ) )
			exit();

		$o = load('smarty');
		$tmpl = $o["smarty"]->getInstance();
		/*@var $tmpl smarty_common*/

		$tmpl->assign ( 'pagetitle', (!empty( $data [ 'pagetitle' ] ) ? $data [ 'pagetitle' ] : 'RMO')) ;
		$tmpl->assign ( 'breadcrumb', $this->breadcrumb() );
		$tmpl->assign ( 'flash', core::flash () ) ;
		$tmpl->assign ( 'session', $_SESSION ) ;

		$uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		$uri = misc::wo_args($uri);

		$tmpl->assign ( 'self', $uri ) ;

		$parent = explode('/', trim($uri,'/'));
		$cnt = count($parent);
		if ($cnt > 1) {
			unset($parent[$cnt - 1]);
			$parent = '/' . implode('/', $parent) . '/';
		} else {
			$parent = '/';
		}

		$tmpl->assign ( 'parent', $parent ) ;

		$tmpl->assign ( $data ) ;

		$content = $tmpl->fetch($path_tmpl) ;

		if (COMPRESS) {
			$content = $this->htmlCompress($content);
		}

		$is_change_ct = false;
		$hl = headers_list();
		for ($i = 0, $ci = sizeof($hl); $i<$ci; $i++) {
			if (strstr( strtolower($hl[$i]), "content-type") !== false) {
				$is_change_ct = true;
				break;
			}
		}

		$time = microtime(true) - $this->timer;
		if (!$is_change_ct && !IS_SHELL) {
//			$content .= "<!-- Execution time: ".$time." -->";
		}

		die($content);

	} // end _render()


	/**
	 * Фильтрация html
	 * @param string $html
	 * @return string
	 */
	public function htmlCompress($html) {
		preg_match_all('!(<(?:code|pre|script).*>[^<]+</(?:code|pre|script)>)!',$html,$pre);
		$html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html);
		$html = preg_replace('#<!-[^\[].+->#', '', $html);
		$html = preg_replace('/[\r\n\t]+/', ' ', $html);
		$html = preg_replace('/>[\s]+</', '><', $html);
		$html = preg_replace('/[\s]+/', ' ', $html);
		if (!empty($pre[0])) {
			foreach ($pre[0] as $tag) {
				$html = preg_replace('!#pre#!', $tag, $html,1);
			}
		}
		return $html;

	}


	/**
	 * Формирование пути для контроллера / шаблона
	 * @param $name Имя метода контроллера (C_Contract/onlineRequest)
	 * @param bool|string $is_template Флаг обработки шаблона (имя шаблона)
	 * @return string Путь в файловой системе к контроллеру / шаблону
	 */
	private function name( $name, $template=false ) {

		list ( $controller, $action ) = explode ( DIRECTORY_SEPARATOR, $name ) ;
		$is_cmf = strstr($controller, "Cmf");
		$subname = split("_", $controller);

		// Путь к файлу контроллера
		if(false === $template) {
		 	$result = PROJECT_CONTROLLERDIR.($is_cmf ? "common/" : "") .
		 		( defined('SERVICE') ? ucfirst(SERVICE) . DIRECTORY_SEPARATOR : '' ) .
		 		$controller . '.php' ;

		// Путь к файлу шаблона
		} else {
			if ($is_cmf) {
				$result = PROJECT_TEMPLATE_CMFSDIR . 'common/' . $name . '.tpl';
			} else {
				$path = ( defined('SERVICE') ? ucfirst(SERVICE) . DIRECTORY_SEPARATOR : '' );
				if( true === $template )
					$result = PROJECT_TEMPLATE_SDIR . $path . $name . '.tpl';
				else
					$result = PROJECT_TEMPLATE_SDIR . $path . $controller .
						DIRECTORY_SEPARATOR . $template . '.tpl';
			}
		}

		return $result ;
	}


	/**
	 * Перенаправление пользователя на заданный URL
	 * @param string $url URL
	 */
	public static function location ( $url ) {
		session_write_close();
		header ( 'Location: ' . $url ) ;
		die ( '<script type="text/javascript">location.href = "' . $url . '";</script>' ) ;
	} // end location()


	/**
	 * Уведомление пользователя сообщением
	 * @param string $value Текст сообщения
	 * @param integer $type Тип сообщения
	 */
	public static function flash ( $value = null, $type = FLASH_NORM ) {
		if (!is_null ( $value )) {
			$_SESSION [ '__flash' ] [] = array(
				"date" => time(),
				"value" => $value,
				"type" => $type
			);
		} else {
			$flash = !empty ( $_SESSION [ '__flash' ] ) ? $_SESSION [ '__flash' ] : '' ;
			$_SESSION [ '__flash' ] = array();
			session_write_close();
			return $flash ;
		}
	} // end flash()


	/**
	 * Получение хлебных крошек для проекта
	 */
	public function breadcrumb() {

		$result = false;
		$uri = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		$uri = misc::wo_args($uri);

		$uri = trim($uri, "/");

		$uri = empty($uri) ? '/' : $uri;

		if (!empty($uri) && !IS_SHELL) {
			if ($uri == '/') {
				$result = array($this->structure['/'][CRUMBNAME]);
			} else {
				$uri = explode('/', $uri);
				$last = count($uri) - 1;
				$path = '';
				foreach($uri as $k => $v) {
					$path .= '/' . $v ;
					$value = false;

					if (isset($this->structure[$path. '/' ][CRUMBNAME])) {
						$value = $this->structure[$path. '/' ][CRUMBNAME];
					} else {
						$structure =  $this->structure;
						krsort($structure);
						foreach($structure as $kk => $vv) {
							if (preg_match('#^' . $kk . '$#', $path . '/')) {
								$value = misc::is($vv[CRUMBNAME], "");
								break;
							}
						}
					}

					if($value)
						$result[$path] = $value;
				}
			}
		}
		return $result;

	} // end breadcrumb()


	/**
	 * Ошибка: документ не найден
	 */
	public static function page404() {
		core::pageError(404);
	}

	/**
	 * Ошибка: доступ запрещён
	 */
	public static function page403() {
		core::pageError(403);
	}

	/**
	 * Ошибка: внутренняя ошибка
	 */
	public static function page503() {
		core::pageError(503);
	}

	/**
	 * Перенаправление пользователя на предыдущий URL
	 */
	public static function goBack() {
		$url = !empty($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : ROOT_URL;
		core::location($url);
	}

	/**
	 * Генерация ошибки
	 * @param int $code Код ошибки
	 */
	private static function pageError($code) {
		global $app;
		switch ($code) {
			case 404:
				$msg = "404 Not Found";
				break;
			case 403:
				$msg = "403 Forbidden";
				break;
			case 503:
				$msg = "503 Service Unavailable";
				break;
			default:
				return false;
		}
		header("HTTP/1.0 ".$msg);
		$app->process("/".$code."/");
		exit();
	}

	private function proceedIncomingData() {
		if (get_magic_quotes_gpc()) {
			$_POST = misc::stripslashes($_POST);
			$_GET = misc::stripslashes($_GET);
			$_REQUEST = misc::stripslashes($_REQUEST);
			$_COOKIE = misc::stripslashes($_COOKIE);
			$_FILES = misc::stripslashes($_FILES);
		}
	}

} // end class core
?>