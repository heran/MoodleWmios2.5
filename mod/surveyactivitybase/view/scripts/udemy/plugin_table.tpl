
    <table>
        <thead><tr>
                <th>{$str->displayname}</th>
                <th>{$str->rootdir}</th>
                <th>{$str->source}</th>
                <th>{$str->versiondb}</th>
                <th>{$str->versiondisk}</th>
                <th>{$str->requires}</th>
                <th>{$str->status}</th>
            </tr></thead>
        <tbody>

            {foreach $plugin_types as $type=>$plugins}
            <tr><td colspan="7">{$str->types[$type]}</td></tr>
            {foreach $plugins as $pluginname=>$plugin}
            {if (not isset($showallplugins) or not $showallplugins) and $plugin->is_uptodate()}
            {else}
            <tr>
                <td >{$plugin->displayname}</td>
                <td >{$plugin->get_dir()}</td>
                <td >{if $plugin->is_standard()}{$str->plugin_source_standard}{else}{$str->plugin_source_extendsion}{/if}</td>
                <td >{$plugin->versiondb}</td>
                <td >{$plugin->versiondisk}</td>

                {if $plugin->is_core_dependency_satisfied($survey_version)}
                {assign tdclass 'requires-ok'}
                {else}
                {assign tdclass 'requires-failed'}
                {/if}
                <td class="{$tdclass}">{$plugin->get_depend_survey_version()}</td>
                <td class="{$plugin->get_status()}">{$plugin->get_status(true)}</td>
            </tr>
            {/if}
            {/foreach}
            {/foreach}

            <tr></tr>


        </tbody>
    </table>