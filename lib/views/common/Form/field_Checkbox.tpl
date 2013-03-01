{if 0 == count($oField->group_values)}
<input type="checkbox" {foreach from=$params key=k item=v}{if $k=="value"}{if $v}checked="checked"{/if}{else}{$k}="{$v}"{/if}{/foreach}>
{else}
	{foreach from=$oField->group_values item="group_value" key="group_key"}
		<input type="checkbox"
			{foreach from=$params key=k item=v}
				{if !in_array($k, array('value', 'delim'))}{$k}="{$v}"{/if}
			{/foreach}
			value="{$group_key}"
			{if is_array($params.value) && in_array($group_key, $params.value)}checked="checked"{/if}
		> {$group_value}{if isset($params.delim)}{$params.delim}{else}<br />{/if}
	{/foreach}
{/if}