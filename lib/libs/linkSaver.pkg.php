<?php

class linkSaver {

	public function proceed($link) {

		$link = trim($link);

		$filename = preg_replace('~(^http://|/.*$)~', '', $link);
		$filename = PROJECT_VAR_TMP . str_replace('.', '_', $filename) . DS . date('Ymd_His');
		misc::check_dir(dirname($filename));
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

?>