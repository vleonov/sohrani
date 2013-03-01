{assign var="do_not_show_rightblock" value=0}
{include file="common/header.tpl" andCSS="request.css"}

{include file="C_Request/_catalog.tpl"}

<div id="leftblock">

<div><img src="/i/line.gif" width="665px" height="1px" alt=""></div>

<table id="request_title" cellpadding="0" cellspacing="0"><tr>
	<td class="left"></td>
	<td class="title">{$item.title}</td>
	<td class="right"></td>
</tr></table>
{if !empty($is_print)}
	<div id="request_print"><a href="#" onclick="window.print();">Печать</a></div>
{/if}
<div class="cleaner"> <!-- --> </div>

{include file='common/flash.tpl'}

<div class="leftblock_content">

{if $item.descr}
	<div>{$item.descr}</div>
{/if}
{if ($item.is_descrfull_active && $item.descrfull)}
	<div style="text-align: right;"><a href="/request/{$item.url}/description/">Описание услуги</a></div>
{/if}

{if sizeof($oForm->getFieldList("request"))}

<a name="content"></a>
	{assign var="file_name" value="`$smarty.const.PROJECT_TEMPLATE_SDIR`/C_Request/_`$item.url`.tpl"}
	{if file_exists($file_name)}
		{include file="C_Request/_`$item.url`.tpl"}
	{/if}

	{FormStart name="request"}
	
	<table class="request_form">
	{foreach from=$oForm->getFieldList() item=oField}
		{assign var=field_name value=`$oField->name`}
		{assign var=field_type value=`$oField->type`}

		{assign var="is_title_near" value=false}
		{assign var="title" value=`$field_titles[$field_name]`}
		{assign var="title" value="<label for='`$field_name`'>`$title`</label>"}
		{if $oField instanceof FormFieldString}
			{assign var=field_class value="wide"}
		{elseif $oField instanceof FormFieldSelect}
			{assign var=field_class value="halfwide"}
		{elseif $oField instanceof FormFieldCheckbox}
			{assign var="is_title_near" value=true}
			{assign var=field_class value=""}
		{else}
			{assign var=field_class value=""}
		{/if}

		<tr>
			<td>{if !$is_title_near}{$title}{/if}</td>
		</tr><tr>
			<td>{if $is_title_near}{$title}{/if}{FormField name=$field_name class=$field_class}</td>
		</tr>
	{/foreach}
		<tr>
			<td align="right">{FormSubmit value="Отправить"}</td>
		</tr>

	</table>

	{FormEnd}

{/if}


</div>
</div>

{if $item.url != "legal_address_economy"}
	{include file="C_Request/_rightblock.tpl"}
{/if}

<div class="cleaner"> <!-- --> </div>

{include file="common/footer.tpl"}