<a name="list"></a>

<table border="0" cellpadding="0" cellspacing="0" class="ready_firms_item">
<tr class="order">
	<td class="left_corner"></td>
	<th><a {if $order.by=='title'}class='{$order.dir}'{/if} href="?order_by=title&order_dir={if $order.by=='title' && $order.dir == 'asc'}desc{else}asc{/if}#list">Название</a></th>
	<th><a {if $order.by=='register_at'}class='{$order.dir}'{/if} href="?order_by=register_at&order_dir={if $order.by=='register_at' && $order.dir == 'asc'}desc{else}asc{/if}#list">Дата регистрации</a></th>
	<th><a {if $order.by=='ifns'}class='{$order.dir}'{/if} href="?order_by=ifns&order_dir={if $order.by=='ifns' && $order.dir == 'asc'}desc{else}asc{/if}#list">ИФНС</a></th>
	<th><a {if $order.by=='license'}class='{$order.dir}'{/if} href="?order_by=license&order_dir={if $order.by=='license' && $order.dir == 'asc'}desc{else}asc{/if}#list">Лицензии</a></th>
	<th nowrap><a {if $order.by=='current_account'}class='{$order.dir}'{/if} href="?order_by=current_account&order_dir={if $order.by=='current_account' && $order.dir == 'asc'}desc{else}asc{/if}#list">Р/С</a></th>
	<th><a {if $order.by=='share_capital'}class='{$order.dir}'{/if} href="?order_by=share_capital&order_dir={if $order.by=='share_capital' && $order.dir == 'asc'}desc{else}asc{/if}#list">Уставной капитал</a></th>
	<th><a {if $order.by=='taxation'}class='{$order.dir}'{/if} href="?order_by=taxation&order_dir={if $order.by=='taxation' && $order.dir == 'asc'}desc{else}asc{/if}#list">Налогооблажение</a></th>
	<th><a {if $order.by=='director'}class='{$order.dir}'{/if} href="?order_by=director&order_dir={if $order.by=='director' && $order.dir == 'asc'}desc{else}asc{/if}#list">Директор</a></th>
	<th><a {if $order.by=='price'}class='{$order.dir}'{/if} href="?order_by=price&order_dir={if $order.by=='price' && $order.dir == 'asc'}desc{else}asc{/if}#list">Цена</a></th>
    <td class="right_corner"></td>
</tr>


{foreach from=$ready_firms item=firm}
	<tr>
		<td colspan="11">&nbsp;</td>
	</tr>

	{if ($order.by == "current_account" && $firm.current_account) || ($order.by == "license" && $firm.license)}
		{assign var="class" value="ready_firms_item_highlight"}
	{else}
		{assign var="class" value="ready_firms_item_grey"}
	{/if}
	<tr class="{$class}">
		<td colspan="11" class="title">{$firm.title}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="5" rowspan="4" class="price"><div>
				цена: <br/> <span class="price">{$firm.price|beauty_number}&nbsp;</span>&nbsp;<span class="rub">руб.</span>
		</div></td>
		<td colspan="2"></td>
		<th>дата регистрации:</th>
		<td colspan="3">{$firm.register_at|date_format:"%d.%m.%Y"}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="2"></td>
		<th>Р/С:</th>
		<td colspan="3">{$firm.current_account}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="2"></td>
		<th>Уставной капитал:</th>
		<td colspan="3">{$firm.share_capital}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="2"></td>
		<th>Налогооблажение:</th>
		<td colspan="3">{$firm.taxation}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="2" rowspan="3"></td>
		<td colspan="3" rowspan="3" align="right"><input type="button" value="Купить" onclick="$('firm_title').value='{$firm.title|escape}'; location='#form'"></td>
		<td colspan="2"></td>
		<th>Директор:</th>
		<td colspan="3">{$firm.director}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="2"></td>
		<th>ИФНС:</th>
		<td colspan="3">{$firm.ifns}</td>
	</tr>
	<tr class="{$class}">
		<td colspan="2"></td>
		<th>Лицензии:</th>
		<td colspan="3">{$firm.license}</td>
	</tr>
	
{/foreach}
	<tr>
		<td colspan="11">&nbsp;</td>
	</tr>
</table>


<a name="form"></a>