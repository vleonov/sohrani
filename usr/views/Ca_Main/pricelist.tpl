{include file="common/admin_header.tpl"}


<div class="admin_h1"><a href="/admin/">������ ��������</a> / �����-���� �� �������</div>
<div class="admin_content">

{FormStart name="pricelist"}
{FormField}
{if !$files}
	{FormSubmit value="��������� �����-����"}
{else}
	{FormSubmit value="�������� �����-����"}
{/if}
{FormEnd}

<br />
<br />

<strong>
������� �����-����:
{if $file}
	<a href="/data/{$file}">{$file}</a>
{else}
�����������
{/if}
</strong>

</div>

{include file="common/footer.tpl"}