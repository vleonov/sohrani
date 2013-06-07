<?php

class U_Link_Parser {

	public function proceed($url, $filelist)
	{
		$DOM = new DOMDocument('1.0', 'UTF-8');
		$DOMDocument = @$DOM->loadHTMLFile($filelist[1]);

		$res = array();

		$base_url = $url;
		$url_info = parse_url($base_url);

		if (isset($url_info['host']) || isset($url_info['path'])) {
			$base_url = U_Misc::is($url_info['schema'], 'http://') . U_Misc::is($url_info['host'], $url_info['path']) . '/';
		}

		if ($DOMDocument) {
			/**
			 * Тайтл
			 */
			$item_list = $DOM->getElementsByTagName('title');
			foreach ($item_list as $item) {
				$res['title'][] = $item->nodeValue;
			}

			/**
			 * Картинки/Логотип
			 */
			$item_list = $DOM->getElementsByTagName('img');
			foreach ($item_list as $item) {
				$src = $item->getAttribute('src');
				$url_info = parse_url($src);
				if (empty($url_info['host'])) {
					$src = $base_url . '/' . $src;
				}
				if ($src) {
					$img = array(
						'src' => $src,
						'alt' => $item->getAttribute('alt'),
						'title' => $item->getAttribute('title'),
					);

					if (empty($res['logo']) && preg_match('/logo/', $src)) {
						$res['logo'] = $img;
					} else {
						$res['img'][md5($src)] = $img;
					}

				}
			}

			/**
			 * H1
			 */
			$item_list = $DOM->getElementsByTagName('h1');
			foreach ($item_list as $item) {
				$res['h1'][] = $item->nodeValue;
			}

			/**
			 * H2
			 */
			if (empty($res['h1'])) {
				$item_list = $DOM->getElementsByTagName('h2');
				foreach ($item_list as $item) {
					$res['h2'][] = $item->nodeValue;
				}
			}

			/**
			 * Мета таги
			 */
			$item_list = $DOM->getElementsByTagName('meta');
			foreach ($item_list as $item) {
				$meta_name = strtolower($item->getAttribute('name'));
				switch ($meta_name) {
					case 'description':
					case 'keywords':
						$res[$meta_name] = $item->getAttribute('content');
						break;
				}
			}
		}

		return $res;

	}

	public function countWords($text)
	{
		$text = preg_replace('/[.!?]/ui', '#', $text);

		$text = explode("#", $text);
		$text = array_filter($text);
		foreach ($text as $i=>$part) {
			$rows = explode("\n", $part);
			$rows = array_map("trim", $rows);
			$rows = array_filter($rows);
			$is_long = false;
			foreach ($rows as $row) {
				$space_cnt = substr_count($row, " ");
				if ($space_cnt >= 2) {
					$is_long = true;
					break;
				}
			}

			if ($is_long) {
				$text[$i] = implode(" ", $rows);
			} else {
				unset($text[$i]);
			}
		}

		$counts = array();
		foreach ($text as $part) {
			$words = preg_replace('/[^a-zа-я0-9]/iu', '#', $part);
			$words = explode("#", $words);
			$words = array_filter($words);

			foreach ($words as $word) {
				$word = mb_strtolower($word, "UTF-8");
//				$word = preg_replace("/[аеёийоуюэяы]+$/iu", "", $word);
				if (mb_strlen($word, "UTF-8") > 2) {
					$counts[$word] = U_Misc::is($counts[$word], 0) + 1;
				}
			}
		}

		arsort($counts);

		return $counts;
	}
}