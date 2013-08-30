<div id="notes" class="ud-notes-box ud-notetaking for-tab3">
    <form id="create-note-form" name="create-note-form" action="{$url->update_note->out(false)}" method="post" class="single-line-form"><input type="hidden" name="isSubmitted" value="1">
        <textarea id="note" class="note-input ud-form note expand34-80 " name="note" placeholder="{$str->type_your_note}"></textarea>
        <div class="bottom none">
            <input type="submit" value="Create">
        </div>
    </form>
    <div id="notes-mask">
        <ul id="notes-list" class="notes-list-box" url="{$url->get_note_list->out(false)}" delete_url="{$url->delete_note->out(false)}">
        </ul>
    </div>
    <div id="download-notes" style="overflow: hidden;">
        <a class="btn" id="download-button" target="_blank" href="">{$str->down_load_notes}</a>
    </div>
</div>