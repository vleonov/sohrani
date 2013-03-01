<?php

class C_Main extends controller {

	public function actionIndex(&$r) {

		$oForm = load("form", true);
		/** @var $oForm form_common */
		$oCard = load("card", true);
		/* @var $oCard Cards_Core*/

		$oForm->init("new");
		$oForm->String->init('link', true);

		if ($oForm->isForm('new') && $oForm->isValid()) {
			$res = $oForm->getValues();


			/**
			 * Скачиваем страницу
			 */
			include_once(PROJECT_LIBSDIR . 'linkSaver.pkg.php');
			$oLinkSaver = new linkSaver();

			$file_list = $oLinkSaver->proceed($res['link']);


			/**
			 * Перекодируем в UTF-8
			 */
			include_once(PROJECT_LIBSDIR . 'linkEncoder.pkg.php');
			$oLinkEncoder = new linkEncoder();

			$file_list = $oLinkEncoder->proceed($file_list);

			/**
			 * Парсим страницу
			 */
			include_once(PROJECT_LIBSDIR . 'htmlParser.pkg.php');
			$oHtmlParser = new htmlParser();

			$page_data = $oHtmlParser->proceed($res['link'], $file_list);

			debug::p($page_data);


			exit();
		}

		$list = $oCard->search('card', array('is_active' => true), array('modified_at' => 'DESC'));
//		$list = $oCard->fullFill(misc::array_get_col($list, 'id'));
		$r += array(
			'list' => $list,
		);

	}

	public function actionError(&$r) {
		$code = $r["info"]["matches"]["code"];
		if ($code == "404") {
			$oRequests = load("requests", true);
			/*@var $oRequests Requests_Core*/

			$emails = $oRequests->search("email", array());
			$r["email"] = $emails[0]["email"];
		}

		$r["pagetitle"] = "Ошибка ".$code;
		$r["render"] = $code;
	}
}
