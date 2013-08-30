
<div>
    <div class="warning">
        {$str->pluginscheckfailed}
    </div>
    {include 'plugin_table.tpl'}
    <div class="warning">
        {$str->pluginschecktodo}
    </div>
    <form action="">
    <input type="submit" value="{$str->continue}" />
    </form>
</div>