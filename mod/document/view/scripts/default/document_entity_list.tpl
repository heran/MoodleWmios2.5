{function get_query_url}
{$url_mod_document}/view.php?base_id={$base->id}{foreach $tmpl as $k=>$v}&{$k}={if $k eq $new_k}{$new_v}{else}{$v}{/if}{/foreach}
{/function}
{if not $slice}
{include 'view_header.tpl'}
<div class="document-view-nav">
    <ul>
        <li class="{if $user_me}on{/if}">
            <span class="fm-icons icon-my"></span>
            <a href="{$url_mod_document}/view.php?cmid={$base->get_cm()->id}&user_me=1" target="_self">{get_string('my_documents','document')}</a>
        </li>
        <li class="{if not $user_me}on{/if}">
            <span class="fm-icons icon-all"></span>
            <a href="{$url_mod_document}/view.php?cmid={$base->get_cm()->id}&user_me=0" target="_self">{get_string('all_documents','document')}</a>
        </li>
    </ul>
</div>
<div class="document-view-main-wrapper">
    <div class="document-view-main">
        <div class="document-view-filter">
            <div class="filter-box">
                <ul class="filter-conditions">
                    <li>
                        <div class="condition-name">{get_string('document_file_extension','local_wmios')}</div>
                        <div class="condition-content">
                            <a  href="{get_query_url new_k='file_extension' new_v=''}" class="fileter-or{if not in_array($tmpl['file_extension'],array_keys($file_extensions))} on{/if}" field="file_extension" value="">{get_string('all')}</a>
                            {foreach $file_extensions as $k=>$v}
                            <a href="{get_query_url new_k='file_extension' new_v=$k}" class="fileter-or{if $tmpl['file_extension']==$k} on{/if}" field="file_extension" value="{$k}">{$v}</a>
                            {/foreach}
                            <div class="fileter-opt-box">
                                <a href="" class="more-conditions">{get_string('more_condtions','document')}</a>
                                {if isset($user_me) and $user_me}
                                <div class="filter-permission">
                                    <a href="{get_query_url new_k='permission' new_v=-5}" class="fileter-or{if $tmpl['permission']==-5} on{/if}" field="permission" value="-5">{get_string('all')}</a>
                                    <a href="{get_query_url new_k='permission' new_v=0}" class="fileter-or{if $tmpl['permission']==0} on{/if}" field="permission" value="0">{get_string('document_entity_permission_private','local_wmios')}</a>
                                    <a href="{get_query_url new_k='permission' new_v=1}" class="fileter-or{if $tmpl['permission']==1} on{/if}" field="permission" value="1">{get_string('document_entity_permission_public','local_wmios')}</a>
                                </div>
                                {/if}
                                <!--<div class="act view-switch">
                                <a href="{get_query_url new_k='thumb' new_v=0}" class="op-tolist fm-icons fileter-or{if not $tmpl['thumb']} on{/if}" field="thumb" value="0">{get_string('view_list','document')}</a>
                                <a href="{get_query_url new_k='thumb' new_v=1}" class="op-tothumbnail fm-icons fileter-or{if $tmpl['thumb']} on{/if}" field="thumb" value="1">{get_string('view_thumb','document')}</a>
                                </div>-->
                            </div>
                        </div>

                    </li>

                    {foreach $base->get_document_fields_by_type(document_field_type::TYPE_SELECT_SINGLE) as $field_type}
                    <li>
                        <div class="condition-name">
                            {$field_type->remark}
                        </div>
                        <div class="condition-content">
                            <a href="{get_query_url new_k=$field_type->name new_v=0}" class="fileter-or{if not $tmpl[$field_type->name]} on{/if}" field="{$field_type->name}" value="0">{get_string('all')}</a>
                            {foreach $field_type->dict->children as $child}
                            <a href="{get_query_url new_k=$field_type->name new_v=$child->id}" class="fileter-or{if $tmpl[$field_type->name]==$child->id} on{/if}" field="{$field_type->name}" value="{$child->id}">{$child->content}</a>
                            {/foreach}
                        </div>
                    </li>
                    {/foreach}

                </ul>
            </div>
        </div>
{/if}
        <div class="document-entity-list-wrapper">
            {if count($des)}
            <p>
                <span>{get_string('name')}</span>
                <span>{get_string('username')}</span>
                <span>{get_string('time')}</span>
                <span>{get_string('document_entity_permission','document')}</span>
                <span>{get_string('action')}</span>
            </p>
            <ul>
                {foreach $des as $de}
                <li>
                    <span><b class="doc-icon {strtolower($de->file_extension)}"></b>{$de->title}</span>
                    <span>{$de->user_name}</span>
                    <span>{date('Y-m-d',$de->create_time)}</span>
                    <span>{get_string("document_entity_permission_`$de->permission`",'local_wmios')}</span>
                    <span>
                        <a class="view-document fancybox.ajax" data-fancybox-width="600" href="{$url_mod_document}/view.php?base_id={$base->id}&key={$de->key}" target="_blank">{get_string('view')}</a>
                        {if $user_me}
                        <a class="delete-document" href="{$url_mod_document}/upload.php?action=delete&base_id={$base->id}&key={$de->key}">{get_string('delete')}</a>
                        <a href="{$url_mod_document}/upload.php?action=edit&base_id={$base->id}&key={$de->key}" target="_self">{get_string('edit')}</a>
                        {/if}
                        {if $de->can_be_download_by_current_user()}
                        <a href="{$url_mod_document}/download.php?base_id={$base->id}&key={$de->key}" target="__blank">{get_string('download')}</a>
                        {/if}
                    </span>
                </li>
                {/foreach}
            </ul>
            <div class="p">
                {$pagingbar}
            </div>

            {else}
            <div class="warning">{get_string('no_more_documents','document')}</div>
            {/if}
        </div>
{if not $slice}
        <div id="document-dialog-wrapper"></div>
    </div>
</div>
{include 'view_footer.tpl'}
{/if}