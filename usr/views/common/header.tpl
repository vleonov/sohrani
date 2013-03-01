<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=windows-1251" />
	<title>{$pagetitle|default:''}</title>

	{strip}<link rel="stylesheet" type="text/css" media="all" href="/combine.php?type=css&amp;files=
	bootstrap.css,base.css,grid.css,style.css
	{if !empty($andCSS)},{$andCSS}{/if}
	" />{/strip}

	{strip}<script type="text/javascript" src="/combine.php?type=javascript&amp;files=jquery-1.4.4.min.js,base.js
    {if !empty($andJS)},{$andJS}{/if}
	"></script>{/strip}

</head>

<body>

<div id="outerWrapper">

    <div id="contentWrapper">
