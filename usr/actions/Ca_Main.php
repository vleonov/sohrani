<?php

class Ca_Main extends controller {

	public function __construct(&$r) {
		if ($r["info"]["controller"] == "Ca_Main" && $r["info"]["action"] == "login") {
			if (!empty($_SESSION["user"])) {
				core::location(ROOT_URL."admin/");
			}
		} elseif (empty($_SESSION["user"])) {
			core::location(ROOT_URL."admin/login/?form_back_url=".THIS_URL);
		}

	}

	public function actionIndex(&$r) {
		$oRequests = load("requests", true);
		/*@var $oRequests Requests_Core*/
		$oForm = load("form", true);
		/*@var $oForm form_common*/
		$oBanners = load("banners", true);
		/*@var $oBanners Banners_Core*/
		$oAdBlocks = load("ad_blocks", true);
		/*@var $oAdBlocks AdBlocks_Core*/

		/**
		 * Форма добавления email в подписку
		 */
		$oForm->init("requests_email");
		$oForm->String->init("email", true, 255, 0, "/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/");
		if ($oForm->isForm() && $oForm->isValid()) {
			$res = $oForm->getValues();
			$oRequests->set("email", $res);
			core::location(THIS_URL);
		}


		/**
		 * Форма добавления баннера
		 */
		$oForm->init("banners");
		$oForm->String->init("banner_title", true);
		$oForm->File->init("file", true, 5*1024*1024, array("swf", "gif", "jpg", "jpeg"));
		if ($oForm->isForm() && $oForm->isValid()) {
			$res = $oForm->getValues();
			$res["title"] = $res["banner_title"];

			$meta = pathinfo($res["file"]);
			$res["type"] = $meta["extension"];
			if ($res["type"] != "swf") {
				list($res["width"], $res["height"]) = getimagesize($res["file"]);
			}
			$file_dir = PROJECT_DATADIR;
			$file_name = "banners/".$meta["filename"].".".$meta["extension"];

			misc::check_dir(dirname($file_dir.$file_name));
			rename($res["file"], $file_dir.$file_name);
			$res["file"] = $file_name;

			$oBanners->set("banners", $res);

			core::location(THIS_URL);
		} else {
			$oForm->setValues(array("banner_title" => "Название"));
		}

		/**
		 * Форма добавление рекламного блока
		 */
		$oForm->init("ad_blocks");
		$oForm->String->init("adblock_title", true);
		$oForm->Text->init("text", true);
		$oForm->String->init("link", true);
		if ($oForm->isForm()) {
			if ($oForm->isValid()) {
				$res = $oForm->getValues();
				$res["title"] = $res["adblock_title"];

				$oAdBlocks->set("ad_blocks", $res);

				core::location(THIS_URL);
			}
		} else {
			$oForm->setValues(array("adblock_title" => "Заголовок", "text" => "Текст/описание", "link" => "Ссылка"));
		}


		$r += array(
			"request_list" => $oRequests->search("requests", array(), array("id" => "ASC")),
			"email_list" => $oRequests->search("email", array(), array("email" => "ASC")),
			"banner_list" => $oBanners->search("banners", array(), array("title" => "ASC")),
			"adblock_list" => $oAdBlocks->search("ad_blocks", array(), array("title" => "ASC")),
		);

	}

	public function actionRequest(&$r) {
		$oRequests = load("requests", true);
		/*@var $oRequests Requests_Core*/
		$oForm = load("form", true);
		/*@var $oForm form_common*/
		$oBanners = load("banners", true);
		/*@var $oBanners Banners_Core*/
		$oAdBlocks = load("ad_blocks", true);
		/*@var $oAdBlocks AdBlocks_Core*/

		$item = $oRequests->getFull($r["info"]["matches"]["id"]);
		$plugin_name = "requests/".misc::is($item["url"], null);
		$oRequestHelper = load($plugin_name);
		if (!$item || !$oRequestHelper[$plugin_name]) {
			core::page404();
		}
		$oRequestHelper = $oRequestHelper[$plugin_name]->getInstance();

		$oForm->init("request");
		$oForm->String->init("title", true);
		$oForm->Wiki->init("descr_wiki", false);
		$oForm->Checkbox->init("is_descrfull_active", false);
		$oForm->File->init("file", false);
		$tmp = $oBanners->search("banners", array(), array("title" => "ASC"));
		for ($i = 0, $ci = sizeof($tmp), $list = array(); $i<$ci; $i++) {
			$list[$tmp[$i]["id"]] = "<a href='/data/".$tmp[$i]["file"]."' target='_blank'>".$tmp[$i]["title"]."</a>";
		}
		$r["is_banners"] = !empty($list);
		$oForm->Checkbox->init("banner_ids", false, $list);
		$tmp = $oAdBlocks->search("ad_blocks", array(), array("title" => "ASC"));
		for ($i = 0, $ci = sizeof($tmp), $list = array(); $i<$ci; $i++) {
			$list[$tmp[$i]["id"]] = $tmp[$i]["title"]." / <i>".$tmp[$i]["text"]."</i>";
		}
		$r["is_adblocks"] = !empty($list);
		$oForm->Checkbox->init("adblock_ids", false, $list);

		$select_field_names = $oRequestHelper->getSelectFieldNames();
		for ($i = 0, $ci = sizeof($select_field_names); $i<$ci; $i++) {
			$oForm->String->init("select_".$select_field_names[$i], false);
		}

		if ($oForm->isForm("request")) {
			if ($oForm->isValid()) {
				$res = $oForm->getValues();

				if ($res["descr_wiki"]) {
					$res["descr"] = $oForm->Wiki->getWikiValue($res["descr_wiki"]);
				}
				if ($res["file"]) {
					$meta = pathinfo($res["file"]);
					$file_dir = PROJECT_DATADIR;
					$file_name = "requests/".$item["url"].".".$meta["extension"];
					misc::check_dir(dirname($file_dir.$file_name));
					rename($res["file"], $file_dir.$file_name);
					$res["file"] = $file_name;
				}
				for ($i = 0, $ci = sizeof($item["banners"]); $i<$ci; $i++) {
					$oRequests->delete("banner", $item["banners"][$i]["id"]);
				}
				if ($res["banner_ids"]) {
					for ($i = 0, $ci = sizeof($res["banner_ids"]); $i<$ci; $i++) {
						$oRequests->set("banner", array("request_id" => $item["id"], "banner_id" => $res["banner_ids"][$i]));
					}
				}

				for ($i = 0, $ci = sizeof($item["adblocks"]); $i<$ci; $i++) {
					$oRequests->delete("adblock", $item["adblocks"][$i]["id"]);
				}
				if ($res["adblock_ids"]) {
					for ($i = 0, $ci = sizeof($res["adblock_ids"]); $i<$ci; $i++) {
						$oRequests->set("adblock", array("request_id" => $item["id"], "adblock_id" => $res["adblock_ids"][$i]));
					}
				}

				for ($i = 0, $ci = sizeof($select_field_names); $i<$ci; $i++) {
					if ($res["select_".$select_field_names[$i]]) {
						$oRequests->set("option", array("request_id" => $item["id"], "field" => $select_field_names[$i], "value" => $res["select_".$select_field_names[$i]]));
					}
				}

				$oRequests->set("requests", $res, $item["id"]);
				core::location(THIS_URL);
			}
		} else {
			if ($item["file"]) {
				$item["file"] = PROJECT_DATADIR.$item["file"];
			} else {
				unset($item["file"]);
			}
			if (!empty($item["banners"])) {
				$item["banner_ids"] = misc::array_get_col($item["banners"], "banner_id");
			}
			if (!empty($item["adblocks"])) {
				$item["adblock_ids"] = misc::array_get_col($item["adblocks"], "adblock_id");
			}
			$oForm->setValues($item);
		}

		$r += array(
			"item" => $item,
			"select_field_names" => $select_field_names,
		) + $oRequestHelper->initAdminRequest();;

	}

	public function actionRequestDescrfull(&$r) {
		$oRequests = load("requests", true);
		/*@var $oRequests Requests_Core*/
		$oForm = load("form", true);
		/*@var $oForm form_common*/

		$item = $oRequests->get("requests", $r["info"]["matches"]["id"]);
		if (!$item) {
			core::page404();
		}

		$oForm->init("request_descrfull");
		$oForm->Wiki->init("descrfull_wiki", false);

		if ($oForm->isForm()) {
			if ($oForm->isValid()) {
				$res = $oForm->getValues();
				$res["descrfull"] = $oForm->Wiki->getWikiValue($res["descrfull_wiki"]);

				$oRequests->set("requests", $res, $item["id"]);
				core::location(THIS_URL);
			}
		} else {
			$oForm->setValues($item);
		}

		$r["item"] = $item;
	}

	public function actionLogin(&$r) {
		$oForm = load("form", true);
		/*@var $oForm form_common*/

		$oForm->init("login", "POST");

		$oForm->String->init("login", true);
		$oForm->Password->init("passwd", true);

		if ($oForm->isForm("login") && $oForm->isValid()) {
			$res = $oForm->getValues();

			$oUsers = load("users", true);
			/*@var $oUsers Users_Core*/

			if (!$user = $oUsers->search("users", array("login" => $res["login"], "passwd" => md5($res["passwd"])))) {
				$oForm->addError("login", "wrong_loginpasswd");
			} else {
				$_SESSION["user"] = $user[0];
				core::location($oForm->back_url());
			}

		}
	}

	public function actionLogot(&$r) {
		unset($_SESSION["user"]);
		core::location(ROOT_URL."admin");
	}

	public function actionDelete(&$r) {
		$oModule = load($r["info"]["matches"]["module"], true);
		$oModule->delete($r["info"]["matches"]["object"], $r["info"]["matches"]["id"]);

		core::location(misc::is($_GET["back_url"], misc::is($_SERVER['HTTP_REFERER'], ROOT_URL."admin/")));
	}

	public function actionReadyFirms(&$r) {
		$oRequests = load("requests", true);
		/*@var $oRequests Requests_Core*/
		$oForm = load("form", true);
		/*@var $oForm form_common*/

		if (!empty($r["info"]["matches"]["id"])) {
			$item = $oRequests->get("data_ready_firms", $r["info"]["matches"]["id"]);
			if (!$item) {
				core::page404();
			}
		} else {
			$item = null;
		}

		$request = $oRequests->search("requests", array("url" => "buy_ready_firms"));
		if (!$request) {
			core::page404();
		}
		$request = $request[0];

		$oForm->init("ready_firms");
		$oForm->String->init("title", true);
		$oForm->Date->init("register_at", true);
		$oForm->String->init("ifns", true);
		$oForm->Text->init("license", true);
		$oForm->Text->init("current_account", true);
		$oForm->String->init("share_capital", true);
		$oForm->String->init("taxation", true);
		$oForm->String->init("director", true);
		$oForm->String->init("price", true);

		if ($oForm->isForm()) {
			if ($oForm->isValid()) {
				$res = $oForm->getValues();

				$oRequests->set("data_ready_firms", $res, $item ? $item["id"] : null);
				core::location("/admin/request/".$request["id"]."/");
			}
		} else {
			$oForm->setValues($item);
		}

		$r += array(
			"request" => $request,
			"item" => $item,
		);
	}

	public function actionPricelist(&$r) {
		$oPricelist = load("pricelist", true);
		/*@var $oPricelist Pricelist_Core*/
		$oPricelist->setSubdir("pricelist");

		$oForm = load("form", true);
		/*@var $oForm form_common*/

		$oForm->init("pricelist");
		$oForm->File->init("file", true);

		$r["file"] = $oPricelist->getFile();

		if ($oForm->isForm("pricelist") && $oForm->isValid()) {
			$res = $oForm->getValues();

			$oPricelist->setFile($res["file"]);
			
			core::flash("Прайс-лист обновлен");
			core::location(THIS_URL);
		}
	}

	public function actionLegalPricelist(&$r) {
		$oRequests = load("requests", true);
		/*@var $oRequests Requests_Core*/

		$oPricelist = load("pricelist", true);
		/*@var $oPricelist Pricelist_Core*/
		$oPricelist->setSubdir("legal_pricelist");

		$oForm = load("form", true);
		/*@var $oForm form_common*/

		$oForm->init("pricelist");
		$oForm->File->init("file", true);

		$r["file"] = $oPricelist->getFile();

		if ($oForm->isForm("pricelist") && $oForm->isValid()) {
			$res = $oForm->getValues();

			$oPricelist->setFile($res["file"]);
			$file = $oPricelist->getFIle();

			$fd = fopen(PROJECT_DATADIR."/".$file, "r");
			//Читаем csv файл по строчно

			//Первые две строки пропускаем - это шапка и заголовки столбцов
			$row = fgetcsv($fd, 2048, ";");
			$row = fgetcsv($fd, 2048, ";");

			//Соответствие колонок в csv колонкам в БД
			$rows = array(
				0 => "district",
				1 => "ifns",
				2 => "index",
				3 => "address",
				4 => "rent_per_11",
				8 => "po_per_month",
				9 => "commission",
				11 => "contract_form",
				12 => "flag_3",
				13 => "flag_1",
				14 => "flag_2",
				15 => "flag_4",
				16 => "flag_5",
				17 => "flag_6",
				18 => "flag_7",
				19 => "flag_8",
				20 => "flag_9",
				21 => "flag_10",
				22 => "flag_11",
				23 => "flag_12",
				24 => "flag_13",
			);

			//Очищаем существующие адреса
			$addresses = $oRequests->search("data_legal_addresses", array());
			for ($i = 0, $ci = sizeof($addresses); $i<$ci; $i++) {
				$oRequests->delete("data_legal_addresses", $addresses[$i]["id"]);
			}

			//Парсим каждую строку и вставляем ее в БД
			while ($row = fgetcsv($fd, 2048, ";")) {
				$item = array();
				foreach ($rows as $i=>$key) {
					$item[$key] = misc::is($row[$i], null);
				}

				$oRequests->set("data_legal_addresses", $item);
			}

			fclose($fd);

			core::flash("Прайс-лист обновлен");
			core::location(THIS_URL);
		}
	}
}

?>