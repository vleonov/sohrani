{if empty($em)}{assign var="em" value=10}{/if}
{if empty($width)}{assign var="width" value=10}{/if}
{if empty($height)}{assign var="height" value=10}{/if}
{assign var="src_width" value=$image.width/$em}
{assign var="src_height" value=$image.height/$em}

{if $width/$src_width < $height/$src_height}
	<img src="/data{$image.file}" width="100%" alt="" />
{else}
	<img src="/data{$image.file}" height="100%" alt="" />
{/if}