{if !empty($params.readonly)}
{include file="common/Form/field_String.tpl"}
{else}
<div class="re-caljs" id="{$oField->name}"></div>
<script type="text/javascript">
	new re_cal('input',{ldelim}'ele': '{$oField->name}', 'listing': 'yes', 'id': '{$oField->name}_id' {if $params.value}, 'InputDefault': '{$params.value}'{/if}{rdelim});
</script>
{/if}