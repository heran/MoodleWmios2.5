{if not $slice}
{include 'view_header.tpl'}
<div class="document-entity-view-wrapper">
{/if}
<div class="upload-view-body {if $slice}slice{/if}">
    <!--upload-view-main-start-->
    <div class="upload-view-main">
        <!--upload-view-doc-header-start-->
        <div class="upload-view-doc-header">

            <div class="doc-header">
                <h1>
                    <span class="ic ic-doc"></span>
                    <span>{$de->title}</span>
                </h1>
                <p class="doc-desc">{$de->summary}</p>
            </div>


        </div>
        <!--upload-view-doc-header-end-->
        <!--upload-view-doc-reader-start-->
        <div class="upload-view-doc-reader">

            <div class="doc-reader">
                <!--doc-reader-tools-start-->
                <div class="doc-reader-tools">
                    <!--<a class="favorite-btn" key="ccb2561843e0374b9daf8b99c7d3bd67" href="/document/favorite/add/key/ccb2561843e0374b9daf8b99c7d3bd67/" title="添加收藏">
                        <span class="ic ic-favorite"></span>{$de->title}
                    </a>
                    <a class="recommend-btn" key="ccb2561843e0374b9daf8b99c7d3bd67" href="/document/recommend/add/key/ccb2561843e0374b9daf8b99c7d3bd67/" title="添加推荐">
                        <span class="ic ic-share"></span>推荐
                    </a>
                    <div class="hidden recommend-reson-box" style="display:none;">
                        <textarea cols="35" rows="3" placeholder="推荐理由"></textarea>
                    </div>-->
                    {if $de->can_be_download_by_current_user()}
                    <a href="{$url_mod_document}/download.php?base_id={$base->id}&key={$de->key}" target="__blank">
                        <span class="ic ic-download"></span>
                        {get_string('download')}
                    </a>
                    {/if}
                    <a href="{$url_mod_document}/upload.php?action=edit&base_id={$base->id}&key={$de->key}">
                        <span class="ic ic-edit"></span>
                        {get_string('edit')}
                    </a>

                </div>
                <!--doc-reader-tools-end-->
                <!--doc-reader-box-start-->
                <div class="doc-reader-box">
                    <iframe src="{trim(get_config('local_wmios','document_apiurl'),'/ ')}/{trim($de->get_preview_url('swf'),'/ ')}"></iframe>
                </div>
                <!--doc-reader-box-end-->


                <!--doc-reader-footer-start-->
                <div class="doc-reader-footer">



                </div>
                <!--doc-reader-footer-end-->



            </div>

        </div>
        <!--upload-view-doc-reader-end-->
        <!--upload-view-doc-footer-start-->
        <div class="upload-view-doc-footer">



        </div>
        <!--upload-view-doc-footer-end-->

    </div>
    <!--upload-view-main-end-->



    <!--upload-view-aside-start-->
    <div class="upload-view-aside">



        <!--doc-info-start-->
        <div class="doc-mod doc-info">


            <div class="inner">

                <div class="doc-title-info">
                    <h4>文档信息</h4>
                </div>
                <div class="doc-info-wrapper">
                    <!--<p>
                        <span class="tail-info">评论：</span>
                        <span class="tail-info">
                            <span id="viewCount-2"></span>次
                        </span>
                    </p>
                    <p>
                        <span class="tail-info">推荐：</span>
                        <span class="tail-info">
                            <span id="viewCount-2"></span>次
                        </span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="tail-info">收藏：</span>
                        <span class="tail-info">
                            <span id="viewCount-2">1</span>次
                        </span>
                    </p>
                    <p>
                        <span class="tail-info">浏览：</span>
                        <span class="tail-info">
                            <span id="viewCount-2">2</span>次
                        </span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="tail-info">下载：</span>
                        <span class="tail-info">
                            <span id="downCount-2"></span>次
                        </span>
                    </p>-->

                    <p id="docCreater">
                        <span class="tail-info">{get_string('author','document')}：</span>
                        {$de->user_name}                        </p>
                    <p class="hidden-info">
                        <span class="tail-info">{get_string('create_time','document')}：</span>{date('Y-m-d',$de->create_time)}
                    </p>
                    <p class="hidden-info">
                        <span class="tail-info">{get_string('update_time','document')}：</span>{date('Y-m-d',$de->update_time)}
                    </p>
                    <p class="hidden-info">
                        <span class="tail-info">{get_string('file_extension','document')}：</span>
                        <b class="ic ic-{$de->file_extension}"></b>
                        <span class="ml5">{$de->file_extension}</span></p>
                    <p class="keyword hidden-info">
                        <span class="tail-info">{get_string('keywords','document')}：</span>
                        {$de->keywords}</p>
                    {foreach $base->get_document_fields() as $field_type}
                    <p class="hidden-info">
                        <span class="tail-info">{$field_type->remark}：</span>
                        {if $field_type->type==document_field_type::TYPE_INPUT_STRING}
                            {$de->getField($field_type->name)}
                        {elseif $field_type->type==document_field_type::TYPE_SELECT_SINGLE}
                            {assign dict $field_type->dict->find_descendent_by_id($de->getField($field_type->name))}
                            {if $dict}
                                {$dict->getContentChain(false)}
                            {/if}
                        {/if}
                    </p>
                    {/foreach}
                </div>

            </div>


        </div>
        <!--doc-info-end-->
        <!--doc-list-start-->
        <div class="doc-mod doc-list">
            <div class="doc-mod-title">
                <h4>相关文档推荐</h4>
            </div>

            <div class="doc-mod-list">

            {if isset($subsidiary['mlt'])}
                <ul>
                    {foreach $subsidiary['mlt'] as $doc}
                    <li>
                        <div class="doc-related-item">
                            <a target="_blank" title="{$doc['title']}" href="{$url_mod_document}/view.php?base_id={$base->id}&key={$doc['key']}" class="ellipsis">
                                <span class="ic ic-{$doc['file_extension']}"></span>
                                <span>{$doc['title']}</span>
                            </a>
                        </div>
                    </li>
                    {/foreach}
                </ul>
            {/if}

            </div>
        </div>
        <!--doc-list-end-->











    </div>
    <!--upload-view-aside-end-->


</div>
{if not $slice}
</div>
{include 'view_footer.tpl'}
{/if}