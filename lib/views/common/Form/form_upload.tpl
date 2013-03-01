{strip}
<script language="JavaScript">
{if $item}
	var result = {ldelim}
		item: {ldelim}
			{foreach from=$item key=k item=v name=l}
				{$k}: '{$v}'{if !$smarty.foreach.l.last},{/if}
			{/foreach}
		{rdelim},
		result: 1
	{rdelim};
{else}
	var result = {ldelim}
		result: 0
	{rdelim};
{/if}

{if !empty($is_init)}
	Form_{$form.name}.uploadResult("{$name}", result);
{else}
	parent.Form_{$form.name}.uploadResult(window.name, result);
{/if}
</script>
{/strip}