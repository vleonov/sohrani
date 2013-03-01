{include file="common/admin_header.tpl"}


<div class="admin_h1"><a href="/admin/">Список разделов</a> / {$item.title}</div>
<div class="admin_content">

{FormStart}
<table>
	<tr>
		<th><label for="title">Заголовок (название раздела):</label></th>
		<td>{FormField name="title" class="wide"}</td>
	</tr>
	<tr>
		<th><label for="descr_wiki">Описание услуги:</label></th>
		<td>{FormField name="descr_wiki" class="wide"}</td>
	</tr>
	<tr>
		<td></td>
		<td>
			{FormField name="is_descrfull_active"}
			<label for="is_descrfull_active">Активировать страницу с дополнительным описанием</label>
			<a href="/admin/request/{$item.id}/descrfull/">Редактировать</a>
		</td>
	</tr>
	<tr>
		<th><label for="file">Загрузить файл с подробным описанием:</label></th>
		<td>{FormField name="file"}</td>
	</tr>
	{if $is_banners}
	<tr>
		<th>Баннеры на странице:</th>
		<td>{FormField name="banner_ids"}</td>
	</tr>
	{/if}
	{if $is_adblocks}
	<tr>
		<th>Рекламные блоки:</th>
		<td>{FormField name="adblock_ids"}</td>
	</tr>
	{/if}

	{if !empty($select_field_names)}
	{foreach from=$select_field_names item=field_name}
		<tr>
			<th>Значения для поля "{$field_titles[$field_name]|default:$field_name}"</th>
			<td>{if (isset($item.options[$field_name]))}
					<ul>
					{foreach from=$item.options[$field_name] item=v}
						<li>{$v.value} <a href="/admin/delete/requests/option/{$v.id}/" class="delete">удалить</a></li>
					{/foreach}
					</ul>
				{/if}
				{FormField name="select_`$field_name`"}
			</td>
		</tr>
	{/foreach}
	{/if}

	<tr>
		<td></td>
		<td>{FormSubmit}</td>
	</tr>
</table>
{FormEnd}

{assign var="file_name" value="`$smarty.const.PROJECT_TEMPLATE_SDIR`/Ca_Main/_`$item.url`.tpl"}
{if file_exists($file_name)}
	{include file="Ca_Main/_`$item.url`.tpl"}
{/if}

</div>

{include file="common/footer.tpl"}