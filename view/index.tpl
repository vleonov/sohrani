{extends file="layout.tpl"}

{block "content"}

    <table id="linkCardContainer">
    {assign var="lazy" value=false}
    {include file="common/_card.tpl" card=$nullCard}
    	{foreach from=$cards item=card key=i}
            {if $i>20}
                {assign var="lazy" value=true}
            {/if}
            {include file="common/_card.tpl"}
    	{/foreach}
    </table>

{/block}