{if not $slice}
{include 'view_header.tpl'}
{/if}
<div class="upload-view">

    <div class="upload-view-crumbs">
        {get_string('search')}'{$searchwords}' {get_string('get_a_results','document',$total)}，以下是{$page*$perpage}-{if ($page+1)*$perpage gt $total}{$total}{else}{($page+1)*$perpage}{/if}条结果
    </div>

    <div class="upload-view-body">

        <div class="search-result-wrapper">

            {if isset($subsidiary['spellcheck'])}
            <div class="spellcheck-box">
                <p>
                    <span>你是不是要找：</span>
                    {foreach $subsidiary['spellcheck'] as $check}
                    <a target="_self" href="{$url_mod_document}/view.php?base_id={$base->id}&searchwords={urlencode($check)}">{$check}</a>
                    {/foreach}
                </p>
            </div>
            {/if}
            {if count($des)}
            <div class="search-result">
                <div class="search-result-box">
                    {foreach $des as $document}
                    <dl class="search-result-item">
                        <dt class="">
                            <span title="{$document->file_extension}" class="ic-{$document->file_extension} ic"></span>
                            {assign title_hl $document->get_highlights('title')}

                            <span><a class="view-document fancybox.ajax" href="{$url_mod_document}/view.php?base_id={$base->id}&key={$document->key}" target="_blank">{if $title_hl}{current($title_hl)}{else}{$document->title}{/if}</a></span>
                            <span>{date('Y-m-d',($document->create_time))}</span>
                            <span>{get_string('author','document')}：{*<a href="" class="Author" target="_blank">*}{$document->user_name}</span>
                        </dt>
                        <dd>
                            {*{assign summary $document->get_highlights('summary')}
                            <p class="summary">{if $summary}{current($summary)}{else}{$document->summary}{/if}</p>*}
                            <p class="file_extract">{current((array)$document->get_highlights('text'))}</p>
                            {*<p class="detail">
                                上传者:
                                <a href="" class="Author" target="_blank">{$document->user_name}</a></p>*}
                        </dd>
                    </dl>

                    {/foreach}


                </div>

                <div class="search-result-paginator">
                    {$pagingbar}
                </div>
            </div>
            {/if}
            {if  isset($subsidiary['terms'])}
            <div class="related-box">
                <p>相关搜索</p>
                <ul>
                    {foreach $subsidiary['terms'] as $term}
                    <li><a target="_blank" href="{$url_mod_document}/view.php?base_id={$base->id}&searchwords={urlencode($term)}">{$term}</a></li>
                    {/foreach}

                </ul>
            </div>
            {/if}


        </div>

    </div>
















</div>
{if not $slice}
{include 'view_footer.tpl'}
{/if}