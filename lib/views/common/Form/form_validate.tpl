{strip}
{ldelim}
	result: {if $is_valid}1{else}0{/if},
	errors: new Array(
	{foreach from=$errors key=name item=l name=l1}
		{foreach from=$l item=e name=l2}
			new Array("{$name}", "{$e}"){if !($smarty.foreach.l1.last && $smarty.foreach.l2.last)},{/if}
		{/foreach}
	{/foreach}
	)
{rdelim}
{/strip}