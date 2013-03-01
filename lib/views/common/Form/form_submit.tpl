<button type="submit" {foreach from=$params key=k item=p}{if $k != 'value'}{$k}="{$p}"{/if} {/foreach} class="btn">

    {$params.value}
</button>