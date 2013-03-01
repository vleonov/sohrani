to: {$email_list}
from: {$smarty.const.MAIL_FROM}
subject: {$item.title}

Поступила заявка:
{$item.title}

---
{foreach from=$request key=field item=value}
{if isset($field_titles[$field])}{$field_titles[$field]}{else}{$field}{/if}: {$value}
{/foreach}
---