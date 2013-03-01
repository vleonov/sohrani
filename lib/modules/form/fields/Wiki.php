<?php
require_once(PROJECT_LIBSDIR."/wackoformatter/classes/WackoFormatter.php");

class FormFieldWiki extends FormFieldText {

	public function proceedParams($params) {
		$params["class"] = misc::is($params["class"], "")." wide for-textarea-resize";

		return parent::proceedParams($params);
	}

	public function getWikiValue($value) {
		if (!trim($value)) return "";
		$parser = @new WackoFormatter();
		$oWikiConfig = @new Ff_Wiki_WikiConfig();
		@$parser->SetObject($oWikiConfig);
		$v = $value;
//		$v = strip_tags($v);
//		$v = str_replace(array("<", ">"), array("&lt;", "&gt;"), $v);
		$res = @$parser->Format($v);
		$res = @$parser->PostFormat($res);
		return $res;
	}
}

class Ff_Wiki_WikiConfig extends WackoFormatterConfigDefault {
	public $config = array(
//		"disable_formatters" => 1,
//		"disable_tikilinks" => true,
//		"disable_wikilinks" => true,
		"allow_rawhtml" => true,
	);

	public function __construct() {
		$this->config["action_path"] = PROJECT_LIBSDIR."/wackoformatter/actions";
	}

	//return unique identifier of this page
	public function GetPageId() { return 0; }

	//preformat links
	public function PreLink($url, $text=false)
	{
		if (!$text) $text = $url;
		return "\xa2\xa2".$url." == ".$text."\xaf\xaf";
	}

	//preformat action
	public function WrapAction($action)
	{
		return "<!--notypo-->\xA1\xA1".$action."\xA1\xA1<!--/notypo-->";
	}

	public function Link($tag, $options, $text)
	{
		$b_text = $text;
		$text = htmlspecialchars($text, ENT_NOQUOTES);

		// change all & symbols to &amp; tag
		$tag = strtr($tag, array("&amp;" => "&"));
		$tag = strtr($tag, array("&" => "&amp;"));

		$imlink = false;
		if (preg_match("/^(http|https|ftp):\/\/([^\\s\"<>]+)\.(gif|jpg|jpe|jpeg|png)$/i", preg_replace("/<\/?nobr>/", "" ,$text)))
			$imlink = $text = preg_replace("/(<|\&lt\;)\/?span( class\=\"nobr\")?(>|\&gt\;)/", "" ,$text);

		if ($imlink) $text = "<img src=\"$imlink\" border=\"0\" title=\"$text\" alt=\"\"/>";

//		if (preg_match("/^(http|https|ftp|file):\/\/([^\\s\"<>]+)\.(gif|jpg|jpe|jpeg|png)$/i", $tag))
//		{// external image
//			$text = preg_replace("/(<|\&lt\;)\/?span( class\=\"nobr\")?(>|\&gt\;)/", "" ,$text);
//			return "<img src=\"".str_replace("&", "&amp;", str_replace("&amp;", "&", $tag))."\" ".($text?"alt=\"".$text."\" title=\"".$text."\"":"")." />";
//		}
//		else
		if (preg_match("/^(http|https|ftp|file|nntp|telnet):\/\/([^\\s\"<>]+)$/", $tag)) {
			// this is a valid external URL
			return '<a href="'.$tag.'" target="_blank">'.$text.'</a>';
		}

		if (($tag!=$b_text) || preg_match('#/#', $tag))
			return '<a href="'.$tag.'">'.$text.'</a>';

		return $text;
	}
}
?>