<?php
class ErrorsCmf extends controller {
	
	public function action404(&$r) {
		header('HTTP/1.1 404 Page not found.');
	}

	public function action403(&$r) {
		header('HTTP/1.1 403 Page forbidden.');

	}
	
	public function action503(&$r) {
		header('HTTP/1.1 503 Service unavailable.');
	}

}
?>