{include file="common/admin_header.tpl"}


<div class="admin_h1"><a href="/admin/">Список разделов</a> / Прайс-лист на главной</div>
<div class="admin_content">

{FormStart name="pricelist"}
{FormField}
{if !$files}
	{FormSubmit value="Сохранить прайс-лист"}
{else}
	{FormSubmit value="Обновить прайс-лист"}
{/if}
{FormEnd}

<br />
<br />

<strong>
Текущий прайс-лист:
{if $file}
	<a href="/data/{$file}">{$file}</a>
{else}
отсутствует
{/if}
</strong>

</div>

{include file="common/footer.tpl"}