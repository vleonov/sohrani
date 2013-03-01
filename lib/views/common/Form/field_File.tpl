<input type="file" {foreach from=$params key=k item=v}{$k}="{$v}" {/foreach} onchange="Form_{$form.name}.uploadFile(this);">
<span style="display: none" id="form_upload_{$params.name}"></span>