{foreach from=$oForm->getFieldList() item=oField}

	{assign var=field_name value=`$oField->name`}
    {if $field_name == "tax_system"}
		{$oField->addParam("onchange", 'if (this.value == \'Другое\') $(\'tax_system_etc\').show(); else $(\'tax_system_etc\').hide();')}
	{elseif $field_name == "tax_system_etc"}
		{$oField->addParam("style", "display: none")}
	{/if}

{/foreach}