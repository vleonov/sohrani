<table class="like_form">

	<tr>
		<th valign="top">������ ������� ����:</th>
		<td>
			{foreach from=$ready_firms item=firm}
				<a href="/admin/ready_firms/{$firm.id}/">{$firm.title}</a>
				<i>
					/ ���� {$firm.price},
					�������� {$firm.director}
				</i>
				<a href="javascript:if (confirm('�� �������, ��� ������ ������� ��� �����')) location='/admin/delete/requests/data_ready_firms/{$firm.id}/?back_url={$smarty.const.ROOT_URL}admin/request/{$item.id}/';" class="delete">�������</a>
				<br/>
			{foreachelse}
				������ ����
			{/foreach}

		</td>
	</tr>
	<tr>
		<td><a href="/admin/ready_firms/">�������� ������� �����</a></td>
	</tr>

</table>