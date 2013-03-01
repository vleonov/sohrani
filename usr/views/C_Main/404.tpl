{include file="common/header.tpl"}

{include file="C_Request/_catalog.tpl"}

<div id="leftblock">

<div><img src="/i/line.gif" width="665px" height="1px" alt=""></div>

<div id="request_title">
	<div class="left"></div>
	<div class="title">Вы находитесь нигде!</div>
	<div class="right"></div>
</div>
<div class="cleaner"> <!-- --> </div>


<div id="leftblock_content">

<p>
Такой страницы нет, возможно Вы ошиблись. Попробуйте вернуться на главную страницу и попробовать снова. Если Вы уверены,
что страница должна быть, пожалуйста, свяжитесь с нами по электронной почте <a href="mailto:{$email}">{$email}</a>.
</p>

<p style="text-align: right">
<a href="{$smarty.const.ROOT_URL}">Вернуться на главную страницу</a>
</p>

</div>

</div>

{include file="common/footer.tpl"}