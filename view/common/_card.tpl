<tr class="linkCard {if !$card->is_saved}unsaved{/if} {if $lazy}a-lazyload{/if}" id="{if !$card->id}linkCardBlank{else}linkCard{$card->id}{/if}" title="{$card->description|default:''}">
	<td class="img">
        {if !empty($card->site)}
            <img {if $lazy}data-src="http://{$card->site}/favicon.ico"{else}src="http://{$card->site}/favicon.ico"{/if} width="16px">
        {else}
            <img src="img/ajax-loader.gif" alt="..." />
        {/if}
    </td>
	<td class="title">
		<a href="{$card->url}">{$card->title|default:$card->url}</a>
	</td>
	<td class="link">{$card->site}</td>
	<td class="panel">
        {if $Auth}
		<button class="btn del" data-href="ajax/card/del/{$card->id}/">
            <i class="icon-remove"></i>
		</button>
        {/if}
	</td>
</tr>