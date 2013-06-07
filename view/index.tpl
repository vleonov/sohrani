{extends file="layout.tpl"}

{block "content"}

    <table id="linkCardContainer">
    {include file="common/_card.tpl" card=''}
        {assign var="lazy" value=false}
    	{foreach from=$cards item=card key=i}
            {if $i>20}
                {assign var="lazy" value=true}
            {/if}
            {include file="common/_card.tpl"}
    	{/foreach}
    </table>

{/block}