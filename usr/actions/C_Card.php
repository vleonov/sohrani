<?php

class C_Card extends controller {

	public function __construct(&$r) {
//		header('Content-Type: text/plain');

		parent::__construct(&$r);
	}

	public function actionRead(&$r) {

		$oCard = load("card", true);
		/* @var $oCard Cards_Core*/

		if (empty($_REQUEST['id'])) {
			return false;
		}

		$id = $_REQUEST['id'];
		$card = $oCard->fullFill($id);

		$r['card'] = $card;

	}

	public function actionSave(&$r) {
		$oCard = load("card", true);
		/* @var $oCard Cards_Core*/

		if (empty($_REQUEST['link'])) {
			return false;
		}

		$link = $_REQUEST['link'];
		$url_info = parse_url($link);
		if (empty($url_info['scheme'])) {
			$link = 'http://' . $link;
		}

		/**
		 * ��������� ��������
		 */
		include_once(PROJECT_LIBSDIR . 'linkSaver.pkg.php');
		$oLinkSaver = new linkSaver();

		$file_list = $oLinkSaver->proceed($link);


		/**
		 * ������������ � UTF-8
		 */
		include_once(PROJECT_LIBSDIR . 'linkEncoder.pkg.php');
		$oLinkEncoder = new linkEncoder();

		$file_list = $oLinkEncoder->proceed($file_list);
//				$file_list = array(
//					'/media/disk/Server/home/sohrani/var/tmp/inspire_2ia_pl/20101221_214337.header',
//					'/media/disk/Server/home/sohrani/var/tmp/inspire_2ia_pl/20101221_214337.encoded',
//				);

		/**
		 * ������ ��������
		 */
		include_once(PROJECT_LIBSDIR . 'htmlParser.pkg.php');
		$oHtmlParser = new htmlParser();

		$page_data = $oHtmlParser->proceed($link, $file_list);


		/**
		 * ��������� ��������� ������
		 */
		if (isset($url_info['host']) || isset($url_info['path'])) {
			$base_url = misc::is($url_info['schema'], 'http://') . misc::is($url_info['host'], $url_info['path']) . '/';
		}
		$url_info = parse_url($base_url);
		$item = array(
			'url' => $link,
			'title' => misc::is($page_data['title'][0], ''),
			'keywords' => misc::is($page_data['keywords'], ''),
			'description' => misc::is($page_data['description'], ''),
			'h1' => implode('#', array_filter( misc::is($page_data['h1'], array()) ) ),
			'h2' => implode('#', array_filter( misc::is($page_data['h2'], array()) ) ),
			'site' => $url_info['host'],
			'is_saved' => true,
			'is_active' => true,
		);

//		$ch = curl_init($url_info['scheme'] . '://' . $url_info['host'] . '/favicon.ico');
//		$favicon = PROJECT_DATADIR . '/' . $url_info['host'] . '/favicon.ico';
//		misc::check_dir(dirname($favicon));
//		$c_fd = fopen($favicon, 'w');
//		$options = array(
//			CURLOPT_AUTOREFERER => true,
//			CURLOPT_FOLLOWLOCATION => true,
//			CURLOPT_HEADER => false,
//			CURLOPT_FILE => $c_fd,
//		);
//		curl_setopt_array($ch, $options);
//		$res = curl_exec($ch);

		$existed = $oCard->search('card', array('url' => $link));

		$id = $oCard->set('card', $item, $existed ? $existed[0]['id'] : null);

		$r += array(
			'id' => $id,
		);
	}

	public function actionDel(&$r) {
		$oCard = load("card", true);
		/* @var $oCard Cards_Core*/

		$id = $r['info']['matches']['id'];
		$oCard->set('card', array('is_active' => false), $id);

		$r += array(
			"result" => true,
		);
	}
}
