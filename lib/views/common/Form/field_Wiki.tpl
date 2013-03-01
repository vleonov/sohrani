<div id="{$oField->name}hook"></div>
<textarea {foreach from=$params key=k item=v}{if $k!="value"}{$k}="{$v}"{/if} {/foreach}>{$params.value|default:""}</textarea>

<script language="JavaScript">

{$oField->name} = new WikiEdit();
{$oField->name}.init("{$oField->name}", "WikiEdit", "edname", "/i/wiki/");

{* Textarea resizer *}
(new Autoarea()).init( "{$oField->name}", "{$oField->name}hook", 150, 50 );

</script>
