{include file="common/header.tpl" andCSS="request.css"}

{include file="C_Request/_catalog.tpl"}

<div id="leftblock">

<div><img src="/i/line.gif" width="665px" height="1px" alt=""></div>

<div id="request_title">
	<div class="left"></div>
	<div class="title">{$item.title|truncate:60:"...":true}</div>
	<div class="right"></div>
</div>
<div class="cleaner"> <!-- --> </div>

{include file='common/flash.tpl'}

<div id="leftblock_content">

<div>{$item.descrfull}</div>
 
</div>
</div>
<div id="rightblock">

<div id="banners_right">
{foreach from=$item.banners item=banner}
	{if $banner.type != "swf"}
		<img src="/data/{$banner.file}" width="310px" height="150px" alt="{$banner.title}">
	{else}
	<object type="application/x-shockwave-flash"
			data="/data/{$banner.file}"
			width="310px"
			height="150px"
	>
	<param name="movie" value="/data/{$banner.file}">
	</object>
	{/if}
{/foreach}
</div>

<br /><br />

<div id="adblocks_right">
{foreach from=$item.adblocks item=adblock}
	<div>
		<a href="{$adblock.link}" target="_block">{$adblock.title}</a> <br />
		{$adblock.text}
	</div>
{/foreach}
</div>

<br /><br />
{if ($item.file)}
	<table id="file_right"><tr>
		<td><img src="/i/pdf.png" width="52px" height="60px"></td>
		<td><a href="/data/{$item.file}">Законодательство</a></td>
	</tr></table>
{/if}

</div>

<div class="cleaner"> <!-- --> </div>

{include file="common/footer.tpl"}