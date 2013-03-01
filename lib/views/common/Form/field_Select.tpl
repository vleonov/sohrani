<select {foreach from=$params key=k item=v}{$k}="{$v}" {/foreach} {if $oField->is_multiple}multiple{/if}>
<option value=""></option>
{foreach from=$oField->options key=key item=item}
	<option value="{$key}" {if ($oField->is_multiple && in_array($key, $oField->value)) || $oField->value==$key}selected{/if}>{$item}</option>
{/foreach}
</select>