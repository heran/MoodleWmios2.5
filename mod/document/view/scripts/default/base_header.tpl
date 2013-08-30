<div class="document-base-wrapper">
    <div id="search" class="search-box">
        <!--<div class="s_logo_wr"> <a href="/" class="s_logo">{$base->name}</a></div>-->
        <div class="s_control_wr">
            <form action="{$url_mod_document}/view.php?cmid={$base->get_cm()->id}" id="topSearchBox" method="get" target="_self">
            <input type="hidden" name="cmid" value="{$base->get_cm()->id}">
                <div>
                    <span class="s_ipt_wr">
                        <input id="kw" name="searchwords" class="s_ipt autocomplete" maxlength="256" tabindex="1" value="{if isset($searchwords)}{$searchwords}{/if}" maxlength="100" autocomplete="off">
                    </span>
                    <span class="s_btn_wr">
                        <input type="submit" id="sb" value="{get_string('search_documents','document')}" class="s_btn" onmouseover="this.className='s_btn s_btn_h'" onmousedown="this.className='s_btn s_btn_d'" onmouseout="this.className='s_btn'">
                    </span>
                    <span class="s_tools"><a href="" rel="" id="exsearch" title="{get_string('advance_search','document')}">{get_string('advance_search','document')}</a></span>
                </div>
                <div>
                    <div class="g-sl">
                        <label for="all">
                            <input type="radio" name="file_extension" value="0" id="all"  checked="checked">
                            {get_string('search_all','document')}</label>

                        <label for="doc">
                            <input type="radio" name="file_extension" value="doc"  id="doc">
                            DOC(X)</label>
                        <label for="ppt">
                            <input type="radio" name="file_extension" value="ppt"  id="ppt">
                            PPT(X)</label>
                        <label for="txt">
                            <input type="radio" name="file_extension" value="txt"  id="txt">
                            TXT</label>
                        <label for="pdf">
                            <input type="radio" name="file_extension" value="pdf"  id="pdf">
                            PDF</label>
                        <label for="xls">
                            <input type="radio" name="file_extension" value="xls"  id="xls">
                            XLS(X)</label>
                    </div>
                    <script type="text/javascript">
                        {if $in_search}
                            $('input[name="file_extension"][value="{$file_extension}"]').attr('checked','checked');
                        {/if}
                        var advance_search = false;
                    </script>
                    <div class="exsearch-box hidden">
                        <!--<div class="g-sl">
                            <label>
                                <input name="position" type="radio" value="0" checked="checked">{get_string('search_all','document')}
                            </label>
                            <label>
                                <input name="position" type="radio" value="text">{get_string('content','document')}
                            </label>
                            <label>
                                <input name="position" type="radio" value="summary"> {get_string('document_entity_summary','document')}
                            </label>
                            <label>
                                <input name="position" type="radio" value="title">{get_string('document_entity_title','document')}
                            </label>
                            <label>
                                <input name="position" type="radio" value="keywords">{get_string('document_entity_keywords','document')}
                            </label>
                            {*{foreach $base->get_document_fields_by_type(document_field_type::TYPE_INPUT_STRING) as $field_type}
                            <label>
                                <input name="position" type="radio" value="{$field_type->name}">{$field_type->remark}
                            </label>
                            {/foreach}*}
                        </div>-->
                        {foreach $base->get_document_fields_by_type(document_field_type::TYPE_SELECT_SINGLE) as $field_type}
                        <div class="g-sl exsearch-box-{$field_type->name}">
                            <input type="hidden" name="{$field_type->name}" value="0">
                            <select>
                                <option level="{$field_type->dict->level+1}" value="0">{get_string('all')}{$field_type->remark}</option>
                                {foreach $field_type->dict->children as $child}
                                <option level="{$child->level}" value="{$child->id}">{$child->content}</option>
                                {/foreach}
                            </select>
                        </div>
                        <script type="text/javascript">
                        searAttrChange('{$field_type->name}',$.parseJSON('{json_encode($field_type->dict->toArray())}'));
                        {if $in_search && isset($tmpl[$field_type->name]) && $tmpl[$field_type->name]}
                        {foreach $field_type->dict->find_descendent_by_id($tmpl[$field_type->name])->getIdChain(true) as $id}
                        $('.exsearch-box-{$field_type->name} option[value={$id}]').parent('select').val('{$id}').change();
                        {/foreach}
                        advance_search = true;
                        {/if}
                        </script>
                        {/foreach}
                        <script type="text/javascript">
                        if(advance_search){
                            $('#exsearch').click();
                        }
                        </script>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="document-base-main">
