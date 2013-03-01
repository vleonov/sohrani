{include file="common/admin_header.tpl"}

<div class="admin_h1"><a href="/admin/">������ ��������</a> / <a href="/admin/request/{$request.id}">������� �����</a> / {if !empty($item)}�������������{else}��������{/if}</div>
<div class="admin_content">

{FormStart name="ready_firms"}
	<table>
		<tr>
			<th>�������� �����:</th>
			<td>{FormField name="title"}</td>
		</tr>
		<tr>
			<th>���� �����������:</th>
			<td>{FormField name="register_at"}</td>
		</tr>
		<tr>
			<th>����:</th>
			<td>{FormField name="ifns"}</td>
		</tr>
		<tr>
			<th>�������� (����� �������):</th>
			<td>{FormField name="license"}</td>
		</tr>
		<tr>
			<th>��������� ����:</th>
			<td>{FormField name="current_account"}</td>
		</tr>
		<tr>
			<th>�������� �������:</th>
			<td>{FormField name="share_capital"}</td>
		</tr>
		<tr>
			<th>���������������:</th>
			<td>{FormField name="taxation"}</td>
		</tr>
		<tr>
			<th>��������:</th>
			<td>{FormField name="director"}</td>
		</tr>
		<tr>
			<th>����:</th>
			<td>{FormField name="price"}</td>
		</tr>
		<tr>
			<td></td>
			<td>
				{FormSubmit}
				{if !empty($item)}
					<input
						type="button"
						class="delete"
						onclick=
							"if (confirm('�� �������, ��� ������ ������� ��� �����')) location='/admin/delete/requests/data_ready_firms/{$item.id}/?back_url={$smarty.const.ROOT_URL}admin/request/{$item.id}/';"
						value="�������"
					/>
				{/if}
			</td>
		</tr>
	</table>
{FormEnd}

</div>

{include file="common/footer.tpl"}