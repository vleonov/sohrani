<?php

class Cr_Card extends controller {

	public function actionSave(&$r) {
		global $argv;

		$oCard = load("card", true);
		/* @var $oCard Cards_Core*/
		
		$id = misc::is($argv[2]);

		if (!$id) {
			return;
		}

		$card = $oCard->get('card', $id);

		/**
		 * Скачиваем страницу
		 */
		include_once(PROJECT_LIBSDIR . 'linkSaver.pkg.php');
		$oLinkSaver = new linkSaver();

		$file_list = $oLinkSaver->proceed($card['url']);


		/**
		 * Перекодируем в UTF-8
		 */
		include_once(PROJECT_LIBSDIR . 'linkEncoder.pkg.php');
		$oLinkEncoder = new linkEncoder();

		$file_list = $oLinkEncoder->proceed($file_list);
//				$file_list = array(
//					'/media/disk/Server/home/sohrani/var/tmp/inspire_2ia_pl/20101221_214337.header',
//					'/media/disk/Server/home/sohrani/var/tmp/inspire_2ia_pl/20101221_214337.encoded',
//				);

		/**
		 * Парсим страницу
		 */
		include_once(PROJECT_LIBSDIR . 'htmlParser.pkg.php');
		$oHtmlParser = new htmlParser();

		$page_data = $oHtmlParser->proceed($card['url'], $file_list);

		
		/**
		 * Сохраняем текстовые данные
		 */
		$url_info = parse_url($card['url']);
		if (isset($url_info['host']) || isset($url_info['path'])) {
			$base_url = misc::is($url_info['schema'], 'http://') . misc::is($url_info['host'], $url_info['path']) . '/';
		}
		$url_info = parse_url($base_url);
		$item = array(
			'title' => misc::is($page_data['title'][0], ''),
			'keywords' => misc::is($page_data['keywords'], ''),
			'description' => misc::is($page_data['description'], ''),
			'h1' => implode('#', array_filter( misc::is($page_data['h1'], array()) ) ),
			'h2' => implode('#', array_filter( misc::is($page_data['h2'], array()) ) ),
			'site' => $url_info['host'],
		);
		$oCard->set('card', $item, $id);

		/**
		 * Сохраняем логотип
		 */
		if (!empty($page_data['logo'])) {
			$meta = getimagesize($page_data['logo']['src']);
			$path_info = pathinfo($page_data['logo']['src']);
			$item = array(
				'logo_file' => '/' . $url_info['host'] . '/' . $id . '/logo.' . $path_info['extension'],
				'logo_width' => $meta[0],
				'logo_height' => $meta[1],
			);
			misc::check_dir(dirname(PROJECT_DATADIR . $item['logo_file']));

			copy($page_data['logo']['src'], PROJECT_DATADIR . $item['logo_file']);
			$oCard->set('card', $item, $id);
		}

		/**
		 * Сохраняем картинки
		 */
		if (!empty($page_data['img'])) {
			$i = 0;
			foreach ($page_data['img'] as $img) {
				$meta = getimagesize($img['src']);
				if ($meta[0] + $meta[1] > 300) {

					$path_info = pathinfo($img['src']);
					$item = array(
						'card_id' => $id,
						'file' => '/' . $url_info['host'] . '/' . $id . '/image' . ($i++) . '.' . $path_info['extension'],
						'width' => $meta[0],
						'height' => $meta[1],
						'title' => ($img['title'] ? $img['title'] : $img['alt']),
					);
					misc::check_dir(dirname(PROJECT_DATADIR . $item['file']));
					copy($img['src'], PROJECT_DATADIR . $item['file']);

					$oCard->set('image', $item);
				}
			}
		}

		/**
		 * Ставим статус - ссылка сохранена
		 */
		$oCard->set('card', array('is_saved' => true), $id);

	}

}

?>