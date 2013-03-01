<table class="like_form">

	<tr>
		<th valign="top">Список готовых фирм:</th>
		<td>
			{foreach from=$ready_firms item=firm}
				<a href="/admin/ready_firms/{$firm.id}/">{$firm.title}</a>
				<i>
					/ цена {$firm.price},
					директор {$firm.director}
				</i>
				<a href="javascript:if (confirm('Вы уверены, что хотите удалить эту фирму')) location='/admin/delete/requests/data_ready_firms/{$firm.id}/?back_url={$smarty.const.ROOT_URL}admin/request/{$item.id}/';" class="delete">Удалить</a>
				<br/>
			{foreachelse}
				список пуст
			{/foreach}

		</td>
	</tr>
	<tr>
		<td><a href="/admin/ready_firms/">Добавить готовую фирму</a></td>
	</tr>

</table>