<div class="activity_list_box">

    <table>
        <thead>
            <tr>
                <th>{$str->name}</th>
                <th>{$str->starttime}</th>
                <th>{$str->endtime}</th>
                <th>{$str->status}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        {foreach $activity_list as $activity_wrapper}
            {assign activity $activity_wrapper->get_activity_instance()}
            <tr>
                <td><a id="surveyactivity-{$activity_wrapper->id}" name="surveyactivity-{$activity_wrapper->id}">{$activity_wrapper->name}</a></td>
                <td>{$activity_wrapper->starttime|date_format:'Y-m-d H:i:s'}</td>
                <td>{$activity_wrapper->endtime|date_format:'Y-m-d H:i:s'}</td>
                <td>{$activity->get_status_display()}</td>
                <td>
                    <a href="{$activity->get_view_url()->out()}">{$str->detail}</a>
                    {if $activity_wrapper->is_complete() and $activity->has_global_report()}
                   <!-- <a href="{$activity->get_global_report_view_url()->out()}">{$str->preiview_report}</a>
                    <a href="{$activity->get_global_report_download_url()->out()}">{$str->download_report}</a>-->
                    {elseif $activity_wrapper->is_new()}
                    <a href="{$url->start_activity->out(false)}&id={$activity_wrapper->id}">{$str->start}</a>
                    {elseif $activity_wrapper->is_ongoing()}
                    <a href="{$url->stop_activity->out(false)}&id={$activity_wrapper->id}">{$str->stop}</a>
                    {/if}
                    <a href="{$url->delete_activity->out(false)}&id={$activity_wrapper->id}">{$str->delete}</a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>