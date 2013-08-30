{include 'base_header.tpl'}
<div class="document-upload-wrapper{if $has_drafts} left{/if}">
    <div class="document-content-wrapper">
        <h2 class="title">{$de->file_name}</h2>
        <div class="upload-add-main">
            {if $is_uploading}
            <div class="upload-file-info">
                <p>{get_string('document_upload_success','document')}：{$de->file_name}</p>
                <p class="size">{get_string('document_file_size','document')}：{sprintf('%d',$de->file_size/1024)}kb</p>
                <p class="action">
                    <a href="{$url_mod_document}/upload.php?action=delete&cmid={$base->get_cm()->id}&key={$de->key}">{get_string('cancel_upload','document')}</a>&nbsp;&nbsp;<a href="">{get_string('download','document')}</a>
                </p>
            </div>
            {/if}
        </div>
        <div class="upload-add-step2">
            <h3 class="header">{if $is_uploading}{get_string('input_document_info','document')}{else}{get_string('edit_document_info','document')}{/if}</h3>
            {$eform->display()}
            <script type="text/javascript">
            {foreach $base->get_document_fields_by_type(document_field_type::TYPE_SELECT_SINGLE) as $field_type}
                editAttrChange('{$field_type->name}',$.parseJSON('{json_encode($field_type->dict->toArray())}'));
                {if $de->getField($field_type->name)}
                {foreach $field_type->dict->find_descendent_by_id($de->getField($field_type->name))->getIdChain(true) as $id}
                $('select.document_fields_change option[value={$id}]').parent('select').val('{$id}').change();
                {/foreach}
                {/if}
            {/foreach}
            </script>
        </div>
    </div>
</div>
{include 'draft.tpl'}
{include 'base_footer.tpl'}