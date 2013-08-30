
{if $has_drafts}

<div class="document-upload-wrapper right">
    <div class="document-draft-wrapper document-content-wrapper">
        <h2 class="title">{get_string('draft_documents','document')}</h2>
        <ul>
            {foreach $drafts as $draft}
            {if isset($de) and ($de->key eq $draft->key)}
            {else}
            <li><a href="{$url_mod_document}/upload.php?action=edit&cmid={$base->get_cm()->id}&key={$draft->key}">{$draft->file_name}</a></li>
            {/if}
            {/foreach}
        </ul>
    </div>
</div>
{/if}