{include file="common/admin_header.tpl"}


<div class="admin_h1"><a href="/admin/">������ ��������</a> / <a href="/admin/request/{$item.id}/">{$item.title}</a> / �������� ������</div>
<div class="admin_content">

{FormStart}
<table>
	<tr>
		<th><label for="descrfull_wiki">�������� ������:</label></th>
		<td>{FormField name="descrfull_wiki" class="wide"}</td>
	</tr>

	<tr>
		<td></td>
		<td>{FormSubmit}</td>
	</tr>
</table>
{FormEnd}

</div>

{include file="common/footer.tpl"}