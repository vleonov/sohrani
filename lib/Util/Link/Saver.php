<?php

class U_Link_Saver {

	public function proceed($link) {

		$link = trim($link);

		$filename = preg_replace('~(^http://|/.*$)~', '', $link);
		$filename = TMP_DIR . '/' . str_replace('.', '_', $filename) . '/' . date('Ymd_His');
		U_Misc::mkdir(dirname($filename));
		$filename_h = $filename . '.header';
		$filename_c = $filename . '.content';
		$ch = curl_init($link);
		$h_fd = fopen($filename_h, 'w');
		$c_fd = fopen($filename_c, 'w');

		$options = array(
			CURLOPT_AUTOREFERER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HEADER => false,
			CURLOPT_FILE => $c_fd,
			CURLOPT_WRITEHEADER => $h_fd,
		);
		curl_setopt_array($ch, $options);

		$res = curl_exec($ch);
		if (!$res) {
			echo curl_error($ch);
		}

		curl_close($ch);
		fclose($h_fd);
		fclose($c_fd);

		return array(
			$filename_h,
			$filename_c,
		);
	}
}