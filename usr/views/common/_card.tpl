<tr class="linkCard {if empty($card.is_saved)}unsaved{/if}" id="{if empty($card)}linkCardBlank{else}linkCard{$card.id}{/if}" title="{$card.description|default:''}">
	<td class="img">{if !empty($card.site)}<img src="http://{$card.site}/favicon.ico" width="16px">{else}<img src="/img/ajax-loader.gif" alt="..." />{/if}</td>
	<td class="title">
		<a href="{$card.url}">{$card.title|default:$card.url}</a>
	</td>
	<td class="link">{$card.site}</td>
	<td class="panel">
		<button class="btn del" data-href="/ajax/card/del/{$card.id}/">
            <i class="icon-remove"></i>
		</button>
	</td>
</tr>