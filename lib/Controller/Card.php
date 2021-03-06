<?php

class C_Card extends Controller {

	public function save() {
		if (!Request()->link) {
			return Response();
		}

		$link = Request()->link;
		$url_info = parse_url($link);
		if (empty($url_info['scheme'])) {
			$link = 'http://' . $link;
		}

		/**
		 * ��������� ��������
		 */
		$oLinkSaver = new U_Link_Saver();

		$file_list = $oLinkSaver->proceed($link);

		/**
		 * ������������ � UTF-8
		 */
		$oLinkEncoder = new U_Link_Encoder();

		$file_list = $oLinkEncoder->proceed($file_list);
//				$file_list = array(
//					'/media/disk/Server/home/sohrani/var/tmp/inspire_2ia_pl/20101221_214337.header',
//					'/media/disk/Server/home/sohrani/var/tmp/inspire_2ia_pl/20101221_214337.encoded',
//				);

		/**
		 * ������ ��������
		 */
		$oHtmlParser = new U_Link_Parser();

		$page_data = $oHtmlParser->proceed($link, $file_list);

		/**
		 * ��������� ��������� ������
		 */
		if (isset($url_info['host']) || isset($url_info['path'])) {
			$base_url = U_Misc::is($url_info['schema'], 'http://') . U_Misc::is($url_info['host'], $url_info['path']) . '/';
		}
		$url_info = parse_url($base_url);
		$item = array(
			'url' => $link,
			'title' => U_Misc::is($page_data['title'][0], ''),
			'keywords' => U_Misc::is($page_data['keywords'], ''),
			'description' => U_Misc::is($page_data['description'], ''),
			'h1' => implode('#', array_filter( U_Misc::is($page_data['h1'], array()) ) ),
			'h2' => implode('#', array_filter( U_Misc::is($page_data['h2'], array()) ) ),
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

		$existed = new L_Cards(array('url' => $link));

        $card = new M_Card($existed->length ? $existed[0]->id : null);
        $card->fromArray($item);

		$id = $card->save();

        return AjaxResponse()->assign('id', $id);
	}

    public function read() {

   		$id = Request()->args(1);
   		$card = new M_Card($id);

   		return Response()
            ->assign('card', $card)
            ->fetch('common/_card.tpl');
   	}

	public function delete() {
		$mCard = new M_Card(Request()->args(1));

		$mCard->is_active = false;
        $mCard->save();

		return AjaxResponse()->assign('result', true);
	}
}
