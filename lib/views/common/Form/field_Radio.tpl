{foreach from=$oField->options key=key item=item name=l}
	<input type="radio" value="{$key}" id="{$oField->name}_{$key}" {foreach from=$params key=k item=v}{if $k!="separator"}{$k}="{$v}" {/if}{/foreach} {if $oField->value==$key}checked{/if}>
	<label for="{$oField->name}_{$key}">{$item}</label>
	{if !$smarty.foreach.l.last}{$params.separator|default:"&nbsp;"}{/if}
{/foreach}