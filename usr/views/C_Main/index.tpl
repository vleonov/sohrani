{include file="common/header.tpl"}

<div id="linkForm">
	{FormStart name='new' onsubmit="LinkForm.submit(); return false;"}
		{FormField placeholder="Link"}
		{FormSubmit value="save"}
	{FormEnd}
</div>


<table id="linkCardContainer">
{include file="common/_card.tpl" card=''}
	{foreach from=$list item=card}
        {include file="common/_card.tpl"}
	{/foreach}
</table>

{include file="common/footer.tpl"}