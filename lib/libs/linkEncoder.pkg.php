<?php

class linkEncoder {

	public function proceed($filelist) {

		$charset = null;

		$this->_getCharsetFromHeaders($filelist[0], $charset);
		$this->_getCharsetFromContent($filelist[1], $charset);

		$encoded_file = $this->_encodeContent($filelist[1], $charset);

		return array(
			$filelist[0],
			$encoded_file,
		);
	}

	protected function _getCharsetFromHeaders($filename_h, &$charset) {
		$headers_raw = file($filename_h);
		$header_list = array();

		for ($i = 0, $ci = sizeof($headers_raw); $i<$ci; $i++) {
			$parts = explode(':', strtolower($headers_raw[$i]));
			if (sizeof($parts) == 2) {
				$parts = array_map('trim', $parts);

				if (strtolower($parts[0]) == 'content-type') {
					$subparts = explode(';', $parts[1]);
					$subparts = array_map('trim', $subparts);
					$header_list[$parts[0]] = $subparts[0];

					if (!empty($subparts[1])) {
						$subparts = explode('=', $subparts[1]);
						if (sizeof($subparts) == 2) {
							$header_list[$subparts[0]] = $subparts[1];
						}
					}
				} else {
					$header_list[$parts[0]] = $parts[1];
				}
			}
		}

		if (isset($header_list['charset'])) {
			$charset = $header_list['charset'];
		}
	}

	protected function _getCharsetFromContent($filename_c, &$charset) {
		$DOM = new DOMDocument();
		$res = $DOM->loadHTMLFile($filename_c);

		if ($res) {
			$meta_list = $DOM->getElementsByTagName('meta');
			foreach ($meta_list as $meta) {
				if ( strtolower($meta->getAttribute('http-equiv')) == 'content-type') {
					$attr = $meta->getAttribute('content');
					if ($attr) {
						$parts = explode(';', $attr);
						if (sizeof($parts) == 2) {
							$subparts = explode('=', $parts[1]);
							if (sizeof($subparts) == 2) {
								$charset = $subparts[1];
								break;
							}
						}
					}
				}
			}
		}
	}

	protected function _encodeContent($filename_c, $charset) {
		$charset = $this->_prependCharset($charset);

		$pathinfo = pathinfo($filename_c);
		$filename = $pathinfo['dirname'] . DS . $pathinfo['filename'] . '.encoded';

		$content = file_get_contents($filename_c);
		$content = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . "\n". $content;
		if ($charset != 'UTF-8') {
			$content = iconv($charset, 'UTF-8', $content);
		}
		file_put_contents($filename, $content);

		return $filename;
	}

	protected function _prependCharset($charset) {
		$charset = strtoupper(trim($charset));

		switch ($charset) {
			case "WIN-1251":
			case "WIN1251":
			case "CP-1251":
			case "CP1251":
				$charset = "WINDOWS-1251";
				break;
		}

		return $charset;
	}
}

?>