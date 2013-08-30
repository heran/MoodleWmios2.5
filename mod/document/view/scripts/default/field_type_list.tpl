<p><a href="{$url_base}&action=field_type&subaction=edit" target="_self">{get_string('add')}</a></p>
<div>
{if count($field_types) gt 0}
<ul>
    {foreach $field_types as $field_type}
    <li>
        <span>{$field_type->name}</span>
        <span>{get_string($field_type->type,MOD_DOCUMENT_PLUGIN_NAME)}</span>
        <span>{$permission_str[$field_type->permission]}</span>
        {if $field_type->is_mine()}
        <span><a href="{$url_base}&action=field_type&subaction=edit&type_id={$field_type->id}" target="_self">{get_string('edit')}</a></span>
            {if $field_type->is_dictionary_type()}
            <span><a href="{$url_base}&action=field_dict&subaction=list&type_id={$field_type->id}" target="_self">{get_string('edit_dictionary',MOD_DOCUMENT_PLUGIN_NAME)}</a></span>
            {/if}
        {/if}
    </li>
    {/foreach}
</ul>
{/if}

</div>