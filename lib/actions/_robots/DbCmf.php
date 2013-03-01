<?php
class DbCmf extends controller {

	/**
	 * Выполняет необходимы up-скрипты в БД
	 */
	public function actionUp(&$r) {
		$oChangelog = load("changelog", true);
		/*@var $oChangelog changelog_common*/
		$oDb = load("pdo", true);
		/*@var $oDb pdo_common*/

		/**
		 * Считываем список изменений из changelog'а БД
		 */
		$sql = "SELECT file FROM :tbl_changelog:T";
		$executed = $oChangelog->select("changelog", $sql, array());
		$executed = misc::array_get_col($executed, "file");

		/**
		 * Считываем рекурсивно список файлов в папке с up-скриптами
		 */
		$files = array();

		$base_dir = PROJECT_ETCDIR."db";
		$stack = array("/");
		while ($dir = array_pop($stack)) {

			//Читаем список файлов в директории
			$list = misc::readdir($base_dir.$dir);

			for ($i = 0, $ci = sizeof($list); $i<$ci; $i++) {

				$item = $dir.$list[$i];
				if (is_dir($base_dir.$item)) {
					//.. если это директория, добавляем ее в стек для чтения
					array_push($stack, $item."/");
				} elseif (substr($item, -4) == ".sql" && !in_array($item, $executed)) {
					//.. если это up sql-файл и он еще не выполнялся, то добавляем его в спиок для выполнения
					$files[$dir][] = $item;
				}
			}

		}
		array_map("sort", &$files);

		/**
		 * Выполняем up-скрипты и пишем изменения в базу
		 */
		if (empty($files)) {
			print "No any update files found\n";
		} else {
			$oDb->beginTransaction();

			$user = get_current_user();
			foreach ($files as $path=>$list) {
				foreach ($list as $file) {
					print $file." ...  ";

					$contents = file_get_contents($base_dir.$file);
					$query_list = split(";[\n\r]", $contents);

					$time = microtime(true);
	
					foreach ($query_list as $query) {
						//Ошибки здесь не перехватываются, т.к. ядро их перехватит, запишет в лог и выведет в stdout.
						//Большего пока не требуется
						$oDb->exec($query);
					}

					$changelog = array(
						"file" => $file,
						"query_at" => date("Y-m-d H:i:s"),
						"query_by" => $user,
						"time" => round(microtime(true) - $time, 3),
					);

					$oChangelog->set("changelog", $changelog);

					print "ok in ".$changelog["time"]." sec\n";
				}
			}
			$oDb->commit();
		}
	}

}
?>