{function name=dict_tree}
<div class="document_field_dict_box">
    <h1>
        <span title="{$data->remark}">{$data->content}</span>
        {if $data->level gt 0}
        <a href="{$url_base}&subaction=edit&dict_id={$data->id}" target="_blank">{get_string('edit')}</a>
        {/if}
        <a href="{$url_base}&subaction=edit&pid={$data->id}" target="_blank">{get_string('add')}</a>
    </h1>
    {if count($data->children) gt 0}
    <ul>
        {foreach $data->children as $child}
        <li>{call name=dict_tree data=$child}</li>
        {/foreach}
    </ul>
    {/if}
</div>
{/function}


<p><a href="{$url_type}" target="_self">{get_string('goto_course_type_list',MOD_DOCUMENT_PLUGIN_NAME)}</a></p>
{call name=dict_tree data=$field_dict_root}