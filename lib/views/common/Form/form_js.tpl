{*

<script language="JavaScript">

var Form_{$form.name} = new FForm('{$form.name}');
var oForm = Form_{$form.name};

oForm.initErrorTitle("required", "Не заполнено необходимое поле");
oForm.initErrorTitle("pattern", "Неверный формат данных");
oForm.initErrorTitle("max_length", "Слишком длинное значение поля");
oForm.initErrorTitle("min_length", "Недостаточно длинное значение поля");
oForm.initErrorTitle("wrong_value", "Неверное значение поля");
oForm.initErrorTitle("file_extension", "Неверное расширение файла");
oForm.initErrorTitle("max_file_size", "Слишком большой файл");

{foreach from=$form.fields item=f}
	{foreach from=$f->getErrorTitle() key=k item=e}
		oForm.initErrorTitle("{$k}", "{$e}", "{$f->name}");
	{/foreach}
{/foreach}

var errors = new Array();
{foreach from=$form.fields item=f}
	{foreach from=$f->getErrors() item=e}
		errors.push(new Array("{$f->name}", "{$e}"));
	{/foreach}
{/foreach}
oForm.initErrors(errors);

</script>

{foreach from=$form.fields item=f}
	{if $f instanceof FormFieldFile && $f->value}
		{include file="common/Form/form_upload.tpl" item=`$f->meta` name=`$f->name` is_init=1}
	{/if}
{/foreach}

*}