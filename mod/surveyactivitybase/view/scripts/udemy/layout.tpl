{* define the menu function for navigation *}
{function name=menu nav=null level=0 id_base='menu_id_base'}
  <ul id="{$id_base}-{$level}" class="" level="{$level}">
  {foreach $nav->children as $child}
      <li>
        <a href="{$child->action->out()}">{$child->get_content(true)}</a>
        {if $child->has_children()}
            {menu nav=$child level=$level+1 id_base=$id_base}
        {/if}
      </li>
  {/foreach}
  </ul>
{/function}


<div class="survey-layout-box">
    <div class="survey-leftrol">
        <div class="block survey-nav-box">
            <h4 class="header"><span class="title">{$str->navigation}</span></h4>
            <div class="content">
                {menu nav=$nav level=0 id_base='survey-nav'}
                <div class="left-button"><a class="button add_activity-button fancybox" href="#my-activity-types-wrapper">{get_string('add_activity','surveyactivitybase')}</a></div>
            </div>
        </div>
        <div class="my-activity-types-wrapper hidden" id="my-activity-types-wrapper" style="display:none;">
            <ul>
            {foreach $base->get_my_activity_types() as $activity_type}
                <li><a href="{$url->add_activity->out()}?cmid={$base->cmid}&add={$activity_type->name}" target="_self">{$activity_type->displayname}</a></li>
            {/foreach}
            </ul>
        </div>
    </div>
    <div class="survey-rightrol">{$maincontent}</div>
</div>