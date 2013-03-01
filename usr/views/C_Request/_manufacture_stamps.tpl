{if empty($is_ajax)}

{foreach from=$stamp_type_list key=key item=title}
	{if $stamp_type == $key}{$title}{else}<a href="{$smarty.const.THIS_URL}?type={$key}">{$title}</a>{/if}<br/>
{/foreach}

{foreach from=$oForm->getFieldList() item=oField}

	{assign var=field_name value=`$oField->name`}
    {if $field_name == "accessories"}
		{$oField->addParam("onchange", 'chooseAccessories(this)')}
	{/if}

{/foreach}

<style type="text/css">{literal}
table.accessories_list {
	width: 100%
}
.accessories_list {
	font-size: 10pt;
}
.accessories_list td {
	padding: 2px;
}
.accessories_list th {
	padding: 2px;
	color: #CCCCCC;
	text-align: center;
	font-weight: normal;
}
{/literal}
</style>

<script type="text/javascript">{literal}

var isAccessoriesInit = false;
var models = new Array();
var isGlobalShow = true;

function chooseAccessories(ele) {
	if (!isAccessoriesInit) {
		var parent = ele.parentNode;
		var span = Builder.node("span", {"id": "accessories_span"}, new Array(
			Builder.node("span", {"id": "accessories_link"}, Builder.node("a", {"class": "local", "onclick": "clickAccessories(true)"}, "Выбрать модель")),
			Builder.node("br"),
            Builder.node("span", {"id": "accessories_list"})
		));
		parent.appendChild(span);

		isAccessoriesInit = true;
	}

	var tip = new Tip("accessories_link", 'content',
		{
		"showOn": "click",
		"closeButton": true,
		"fixed": true,
		"hideOn": false,
		"title": "Доступные модели",
		"ajax": {
			"url": "/request/manufacture_stamps/?stamp_type_id="+ele.value,
			"options": {
		        "onComplete": function(transport) {
					clickAccessories(false);
				}
			}
		},
		"offset": {"x":-200, "y":-50}
		}
	);

	if (ele.value != "") {
		$("accessories_span").show();
	} else {
	    $("accessories_span").hide();
	}
    models = new Array();
	updateModels();
	isGlobalShow = true;
}

function clickAccessories(is_show) {
	$("accessories_list").innerHTML = isGlobalShow && is_show ? "<i>Загрузка...</i>" : "";
	isGlobalShow = false;
}

function chooseModel(title) {
	for (i = 0, ci = models.length; i<ci; i++) {
		if (models[i] == title) {
			return false;
		}
	}
	models.push(title);
	updateModels();
}
function deleteModel(title) {
	var is_start = false;
	for (i = 0, ci = models.length; i<ci; i++) {
		if (models[i] == title) {
			models[i] = "";
			is_start = true;
		} else if (is_start) {
			models[i-1] = models[i];
		}
	}
	models.pop();
	updateModels();
}

function updateModels() {
	var ele1 = $("accessory_models");
	var ele2 = $("accessories_list");
	ele1.value = "";
	msg = "";

	for (i = 0, ci = models.length; i<ci; i++) {
		ele1.value += models[i]+", ";
		msg += models[i]+" (<a onclick='deleteModel(\""+ models[i] +"\")' style='color: red; text-decoration: none; cursor: pointer'>X</a>)<br/>";
	}

    ele2.innerHTML = msg;

	ele2.innerHTML += "<br/>";
}

{/literal}</script>


{else}
<table class="accessories_list"><tr><td valign="top">
<table>
	<tr>
		<th>Модель</th>
		<th>Фото</th>
		<th>Размер поля/Диаметр</th>
		<th>Цена</th>
	</tr>
	{foreach from=$models item=m}
	<tr>
		<td><a class="local" onclick="chooseModel('{$m.title}')">{$m.title}</a></td>
		<td><img src="/i/empty.gif" width="14px" height="14px" style="cursor: pointer" onclick="$('accessories_image').src='/data/{$m.file}'"></td>
		<td>{$m.size}</td>
		<td>{$m.price}</td>
	</tr>
	{/foreach}
</table>
</td><td><img src="/i/blank.gif" width="202px" height="202px" id="accessories_image" style="border: 1px solid #CCCCCC"></td></tr>
</table>
{/if}