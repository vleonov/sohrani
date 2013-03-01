{include file="common/admin_header.tpl"}

<div class="admin_h1">Список разделов</div>
<div class="admin_content">

<table class="catalog">
	<tr><td>
		{foreach from=$request_list key=i item=item}
			{if ($i+1) % 8 == 0}
				</td><td>
			{/if}
			<a href="/admin/request/{$item.id}/">{$item.title}</a> <br/><br/>

		{/foreach}
	</td></tr>
</table>
</div>

<div class="admin_h1">Прайс-листы</div>
<div class="admin_content">
<a href="/admin/pricelist/">Прайс лист на главной</a> <br />
<a href="/admin/legal_pricelist/">Прайс лист юридических адресов</a> <br />
</div>


<div class="admin_h1">Настройки</div>
<div class="admin_content">
	<h3><i>Подписать e-mail на рассылку заявок</i></h3>
	{FormStart name="requests_email"}
	<table>
		{foreach from=$email_list item=item}
			<tr>
				<td>{$item.email}</td>
				<td><a href="javascript:if (confirm('Вы уверены, что хотите удалить email?')) location='/admin/delete/requests/email/{$item.id}/'" class="delete">удалить</a></td>
			</tr>
		{/foreach}

			<tr>
				<td>{FormField}</td>
				<td>{FormSubmit value="Добавить"}</td>
			</tr>
    </table>
    {FormEnd}


	<br />
	<h3><i>Баннеры</i></h3>
	{FormStart name="banners"}
	<table>
		{foreach from=$banner_list item=item}
			<tr>
				<td><a href="/data/{$item.file}" target="_blank">{$item.title}</a></td>
				<td><a href="javascript:if (confirm('Вы уверены, что хотите удалить баннер?')) location='/admin/delete/banners/banners/{$item.id}/'" class="delete">удалить</a></td>
			</tr>
		{/foreach}
		<tr>
			<td>{FormField}</td>
			<td>{FormField}</td>
			<td>{FormSubmit value="Добавить"}</td>
		</tr>
    </table>
    {FormEnd}

    <br />
	<h3><i>Рекламные блоки</i></h3>
	{FormStart name="ad_blocks"}
	<table>
		{foreach from=$adblock_list item=item}
			<tr>
				<td><a href="{$item.link}" target="_blank">{$item.title}</a></td>
				<td>{$item.text}</td>
				<td><a href="javascript:if (confirm('Вы уверены, что хотите удалить рекламный блок?')) location='/admin/delete/ad_blocks/ad_blocks/{$item.id}/'" class="delete">удалить</a></td>
			</tr>
		{/foreach}
		<tr>
			<td>{FormField}</td>
			<td>{FormField}</td>
			<td>{FormField}</td>
			<td>{FormSubmit value="Добавить"}</td>
		</tr>
    </table>
    {FormEnd}
</div>

{include file="common/footer.tpl"}