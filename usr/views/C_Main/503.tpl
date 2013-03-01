{include file="common/header.tpl"}

{include file="C_Request/_catalog.tpl"}

<div id="leftblock">

<div><img src="/i/line.gif" width="665px" height="1px" alt=""></div>

<div id="request_title">
	<div class="left"></div>
	<div class="title">Случилось непредвиденное!</div>
	<div class="right"></div>
</div>
<div class="cleaner"> <!-- --> </div>


<div id="leftblock_content">

<p>
На странице произошла непредвиденная ошибки и Ваш запрос не может быть обработан. Попробуйте вернуться на главную страницу
и попробовать снова.
</p>

<p style="text-align: right">
<a href="{$smarty.const.ROOT_URL}">Вернуться на главную страницу</a>
</p>

</div>

</div>

{include file="common/footer.tpl"}