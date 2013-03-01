<?
class mailer_common {

	private $codings = array(
		"WINDOWS-1251",
		"CP1251",
		"KOI8-R",
		"KOI8R",
		"UTF8",
		"UTF-8",

	);

	private $iconv_method = "//TRANSLIT";
	
	private $tpl_dir = "mails/";
	
	public function getInstance() {
		return $this;
	}

	/**
	 * Отправка письма с телом, заданным в шаблоне $template.
	 * В самом начале шаблона указываются адресаты (To:), отправитель (From:) и тема письма (Subject:).
	 * От тела письма эти заголовки отделяются двумя или большим количеством переводов строки.
	 *
	 * @param string $template	относительный путь до шаблона (от $oSmarty->TEMPLATES_DIR)
	 * @param array $data		данные для шаблона
	 * @param string $coding	кодировка письма
	 *
	 * @return void
	 */
	public function send($template, $data=array(), $coding="windows-1251") {
		$coding = strtoupper($coding);
		if (!in_array($coding, $this->codings))
			$coding = current($this->codings);
		if ($coding!="WINDOWS-1251" && $coding!="CP1251")
			$recode = true;
		else $recode = false;
		
		$template = $this->tpl_dir.$template;

		$oSmarty = load("smarty", true);
		if (is_array($data))
			foreach ($data as $k=>$v)
				$oSmarty->assign($k, $v);
		$body = $oSmarty->fetch($template);
		$body = $this->parseMail($body);

		$t = array();
		if (empty($body["from"])) $body["from"] = MAILER_FROM;
		preg_match("/(^(.*)\s+)?([^\s]+)$/", $body["from"], $t);
		$name = trim($t[2]);
		$mail = trim($t[3]);
		$body["from"] = ($name?"=?".$coding."?b?".base64_encode(iconv("WINDOWS-1251", $coding.$this->iconv_method, $name))."?= ":""). $mail;

		$recipients = explode(",", $body["to"]);
		$body["to"] = array();
		foreach ($recipients as $k=>$v) {
			preg_match("/(^(.*)\s+)?([^\s]+)$/", $v, $t);
			$name = trim($t[2]);
			$mail = trim($t[3]);
			if ($recode && $name)
				$name = iconv("WINDOWS-1251", $coding.$this->iconv_method, $name);
			$body["to"][] = ($name?"=?".$coding."?b?".base64_encode($name)."?= ":""). $mail;
		}

		$template_html = preg_replace("/(\.[^\.]*)$/", "_html\\1", $template);
		if ($oSmarty->template_exists($template_html)) {
			$oSmarty->assign("subject", $body["subject"]);
			$oSmarty->assign("coding", $coding);
			$body_html = $oSmarty->fetch($template_html);
		}
		$oSmarty->clear_all_assign();

		if ($recode) {
			$body["body"] = iconv("WINDOWS-1251", $coding.$this->iconv_method, $body["body"]);
			if(isset($body_html)) $body_html = iconv("WINDOWS-1251", $coding.$this->iconv_method, $body_html);
			$body["subject"] = iconv("WINDOWS-1251", $coding.$this->iconv_method, $body["subject"]);
			if (!empty($from_name))
				$from_name = iconv("WINDOWS-1251", $coding.$this->iconv_method, $from_name);
		}

		$log = array(
			"to" => implode(", ", $body["to"]),
			"from" => $body["from"],
			"subject" => iconv($coding, "WINDOWS-1251".$this->iconv_method, $body["subject"]),
			"msg_length" => strlen($body["body"]),
			"coding" => $coding,
		);

		if (!empty($body["subject"]))
			$body["subject"] = "=?".$coding."?b?".base64_encode($body["subject"])."?=";
		else $body["subject"] = "";

		if (empty($body_html)) {
			$headers = "MIME-Version: 1.0\r\n".
						"X-Mailer: PHP\r\n".
						"From: ".$body["from"]."\r\n".
						"Content-Type: text/plain; charset=".$coding."\r\n".
						"Content-Transfer-Encoding: 8bit";
			$log["result"] = mail(implode(", ", $body["to"]), $body["subject"], $body["body"], $headers);
		} else {
			$boundary = "Message-Boundary-".uniqid();

			$headers = "From: ".$body["from"]."\n";
			$headers.= "MIME-Version: 1.0\n";
			$headers.= "Content-type: multipart/alternative;\n";
			$headers.= " boundary=\"{$boundary}\"\n\n";

			$msg = "--".$boundary."\n";
			$msg.= "Content-Type: text/plain; charset=".$coding."\n";
			$msg.= "Content-Transfer-Encoding: 8bit\n\n";
			$msg.= $body["body"]."\n\n";

			$msg.= "--".$boundary."\n";
			$msg.= "Content-type: text/html; charset=".$coding."\n";
			$msg.= "Content-transfer-encoding: 8bit\n\n";
			$msg.= str_replace("", "", $body_html)."\n\n";

			$msg.= "--".$boundary."--\n";

			$log["result"] = mail(implode(", ", $body["to"]), $body["subject"], $msg, $headers);

		}
	}

	/**
	 * Разбирает письмо на header-данные (from, to, subject) и тело письма (body). Используется для подготовки письма, полученного на основе шаблона, к отправки.
	 *
	 * @param string $body письмо
	 * @return array
	 */
	private function parseMail($body) {
		$res = array();
		$body = trim($body);
		$body = explode("\n", $body);
		$headers_is_exist = false;

		for($i=0, $ci=sizeof($body); $i<$ci; $i++) {
			$body[$i] = str_replace("\r", "", $body[$i]);
			if ("" == trim($body[$i])) break;
			if (preg_match('/^(from|to|subject):(.+)$/i', trim($body[$i]), $m)) {
				$res[strtolower($m[1])] = trim($m[2]);
				$headers_is_exist = true;
			}
		}

		if ($headers_is_exist)
			$body = array_slice($body, ($i+1));
		$res["body"] = implode("\n", $body);

		return $res;
	}

}
?>