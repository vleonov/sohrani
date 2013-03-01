<style type="text/css">
{literal}
.addresses div {
	width: 12px;
	height: 12px;
	cursor: help;
}
.addresses a.buy {
    padding-left: 15px;
	background: url("/i/basket.gif") no-repeat left;
}
.addresses td {
	padding: 3px;
}

.icons td {
	padding: 0px;
	margin: 0px;
}
{/literal}
{section name=foo start=1 loop=14 step=1}
	{assign var=i value=`$smarty.section.foo.index`}
	{math equation="(i-1) * -12" i=$i assign=k}
.flag_{$i}_1 {ldelim}background: url("/i/legal_icons.gif") no-repeat {$k}px 0px;{rdelim}
.flag_{$i}_0 {ldelim}background: url("/i/legal_icons.gif") no-repeat {$k}px -12px;{rdelim}
{/section}
</style>

</div>
</div>
{include file="C_Request/_rightblock.tpl"}
<div>
<div class="leftblock_content">

<a name="addresses"></a>

<table border="0" class="addresses" cellpadding="0" cellspacing="0">
<tr class="order">
	<td class="left_corner"></td>
	<th><a {if $order.by=='district'}class='{$order.dir}'{/if} href="?order_by=district&order_dir={if $order.by=='district' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">Округ</a></th>
	<th><a {if $order.by=='ifns'}class='{$order.dir}'{/if} href="?order_by=ifns&order_dir={if $order.by=='ifns' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">ИФНС</a></th>
	<th><a {if $order.by=='address'}class='{$order.dir}'{/if} href="?order_by=address&order_dir={if $order.by=='address' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">Юридический адрес</a></th>
	<th>Опции</th>
	<th><a {if $order.by=='contract_form'}class='{$order.dir}'{/if} href="?order_by=contract_form&order_dir={if $order.by=='contract_form' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">Форма договора</a></th>
	<th><a {if $order.by=='po_per_month'}class='{$order.dir}'{/if} href="?order_by=po_per_month&order_dir={if $order.by=='po_per_month' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">Стоимость ПО в месяц, руб.</a></th>
	<th><a {if $order.by=='rent_per_11'}class='{$order.dir}'{/if} href="?order_by=rent_per_11&order_dir={if $order.by=='rent_per_11' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">Аренда за 11 месяцев, руб.</a></th>
	<th><a {if $order.by=='commission'}class='{$order.dir}'{/if} href="?order_by=commission&order_dir={if $order.by=='commission' && $order.dir == 'asc'}desc{else}asc{/if}#addresses">Стоимость услуги, руб.</a></th>
	<th></th>
    <td class="right_corner"></td>
</tr>
{foreach from=$addresses item=v}
	<tr style="{cycle values=",background: #E6E6F0"}">
		<td></td>
		<td>{$v.district}</td>
		<td>{$v.ifns}</td>
		<td>{$v.address}</td>
		<td nowrap="nowrap"><table cellpadding="0" cellspacing="0" class="icons"><tr>
			<td><div class="flag_1_{if $v.flag_1}1{else}0{/if}" title="Подтверждение от собственника: {if $v.flag_1}да{else}нет{/if}"></div></td>
			<td><div class="flag_2_{if $v.flag_2}1{else}0{/if}" title="Гарантия от собственника: {if $v.flag_2}да{else}нет{/if}"></div></td>
			<td><div class="flag_3_{if $v.flag_3}1{else}0{/if}" title="Предоставление рабочего места при выездной проверке: {if $v.flag_3}да{else}нет{/if}"></div></td>
			<td><div class="flag_4_{if $v.flag_4}1{else}0{/if}" title="Почтовое обслуживание: {if $v.flag_4}да{else}нет{/if}"></div></td>
			<td><div class="flag_5_{if $v.flag_5}1{else}0{/if}" title="Юридический адрес отсутствует в черном списке: {if $v.flag_5}да{else}нет{/if}"></div></td>
			<td><div class="flag_6_{if $v.flag_6}1{else}0{/if}" title="Адрес не является массовым: {if $v.flag_6}да{else}нет{/if}"></div></td>
			<td><div class="flag_7_{if $v.flag_7}1{else}0{/if}" title="Организация секретарского обслуживания: {if $v.flag_7}да{else}нет{/if}"></div></td>
			<td><div class="flag_8_{if $v.flag_8}1{else}0{/if}" title="Возможность пролонгирования договора: {if $v.flag_8}да{else}нет{/if}"></div></td>
			<td><div class="flag_9_{if $v.flag_9}1{else}0{/if}" title="Возможна оплата по безналичному расчету: {if $v.flag_9}да{else}нет{/if}"></div></td>
			<td><div class="flag_10_{if $v.flag_10}1{else}0{/if}" title="Есть фотографии: {if $v.flag_10}да{else}нет{/if}"></div></td>
			<td><div class="flag_11_{if $v.flag_11}1{else}0{/if}" title="Содействие собственника в решении вопросов в ИФНС: {if $v.flag_11}да{else}нет{/if}"></div></td>
			<td><div class="flag_12_{if $v.flag_12}1{else}0{/if}" title="Площадь помещения: {$v.flag_12}"></div></td>
			<td><div class="flag_13_{if $v.flag_13}1{else}0{/if}" title="Скидка: {$v.flag_13}"></div></td>
		</tr></table></td>
		<td>{$v.contract_form}</td>
		<td>{$v.po_per_month}</td>
		<td>{$v.rent_per_11}</td>
		<td>{$v.commission}</td>
		<td><a href="#form" onclick="$('legal_address').value='{$v.address}';" class="buy">Купить</a></td>
        <td></td>
	</tr>
{/foreach}
</table>

<a name="form"></a>