{include 'base_header.tpl'}
<script type="text/javascript">
var document_view_list_get_params = {$get_params};
</script>
<div class="document-view-topaction">
    <a href="{$url_mod_document}/upload.php?cmid={$base->get_cm()->id}" target="_blank">{get_string('upload_document','document')}</a>
    {assign goto_other_base $base->get_course_document_base_navigation_option_array()}
    {if count($goto_other_base)}
    <span>{get_string('goto_other_document_base','document')}
    <select onchange="window.location.href=this.options[this.selectedIndex].value">
        <option>&nbsp;</option>
        {foreach $goto_other_base as $c=>$v}
        <option value="{$url_mod_document}/view.php?base_id={$c}" {if $base->id eq $c}selected="selected"{/if}>{$v}</option>
        {/foreach}
    </select>
    </span>
    {/if}
</div>
<div class="document-view-wrapper">
    <h2 class="title"><a href="{$url_mod_document}/view.php?base_id={$base->id}">{$base->name}</a></h2>
    <div class="document-view-outter">