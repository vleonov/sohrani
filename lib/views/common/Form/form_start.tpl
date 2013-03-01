<form {foreach from=$params key=k item=p}{$k}="{$p}" {/foreach} onsubmit="return Form_{$form.name}.check();" class="form form-inline well">
<input type="hidden" name="form_name" id="form_name" value="{$form.name}">
<input type="hidden" name="form_back_url" id="form_back_url" value="{$form.back_url}">
<input type="hidden" name="form_action" id="form_action" value="">