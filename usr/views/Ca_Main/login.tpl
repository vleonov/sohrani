{include file="common/admin_header.tpl"}

<div id="form_login">
{FormStart name="login"}
<div>{FormField name="login"}</div>
<div>{FormField name="passwd"}</div>
{FormErrorTitle field="login" wrong_loginpasswd="Невернаая пара логин/пароль"}
{FormEnd value="Войти"}
</div>

{include file="common/footer.tpl"}