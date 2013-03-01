<a name="data_models"></a>

<style type="text/css">
/**
 * Data stamp types
 */
{literal}
table.data_stamp_types {
	border: 2px solid #ADE0E9;
	margin-bottom: 20px;
}
table.data_stamp_types tr.first_tr td {
	border-bottom: 2px solid #ADE0E9;
	background: #ADE0E9;
}
table.data_stamp_types td, table.data_stamp_types th {
	padding: 5px;
	margin: 0;
}
{/literal}
</style>

<table class="like_form">
	<tr>
		<th>Типы оснастки:</th>
		<td>
			{foreach from=$data_stamp_types key=id item=type}
				{FormStart name="data_models_`$id`"}
				<table class="data_stamp_types" cellpadding="0" cellspacing="0">
					<tr class="first_tr">
						<td colspan="3">{FormField name="type_title_`$id`" class="halfwide"}</td>
						<td>{if !sizeof($type.models)}
							<a href="javascript: if (confirm('Вы уверены, что хотите удалить этот тип?'))
									location='/admin/delete/requests/data_stamp_types/{$id}/?back_url={$smarty.const.THIS_URL}#data_models'" class="delete">удалить</a>
						{/if}</td>
						<td></td>
					</tr>
					<tr>
						<th style="text-align: center">Название модели</th>
						<th style="text-align: center">Диаметр</th>
						<th style="text-align: center">Цена</th>
						<th style="text-align: center">Файл</th>
					</tr>
					{foreach from=$type.models item=model}
					<tr>
						<td>{FormField name="model_title_`$id`_`$model.id`"}</td>
						<td>{FormField name="model_size_`$id`_`$model.id`" size="6"}</td>
						<td>{FormField name="model_price_`$id`_`$model.id`" size="6"}</td>
						<td>{FormField name="model_file_`$id`_`$model.id`"}</td>
						<td><a href="" class="delete">удалить</a></td>
					</tr>
					{/foreach}
					<tr>
						<td>{FormField name="model_title_`$id`"}</td>
						<td>{FormField name="model_size_`$id`" size="6"}</td>
						<td>{FormField name="model_price_`$id`" size="6"}</td>
						<td>{FormField name="model_file_`$id`"}</td>
						<td>{FormSubmit}</td>
					</tr>
				</table>
				{FormEnd}
			{/foreach}

			{FormStart name="data_models"}
			{FormField name="type_title"}
			{FormEnd value="Добавить оснастку"}
		</td>
	</tr>
</table>