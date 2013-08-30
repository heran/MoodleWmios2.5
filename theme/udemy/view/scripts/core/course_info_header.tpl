
<div class="top">

    <div class="left-col">
        <div class="course-image" style="background-image: url({$cover_url})"></div>
        <hgroup class="titles">
            <h1 class="ellipsis"><a href="{$course_view_url->out(false)}" target="_self">{$course_fullname}</a></h1>
            <h2 class="ellipsis">
                {get_string('teachers')}: <b>{$manager_str}</b>
            </h2>
        </hgroup>
    </div>
    <div class="right-col">

        <!--<div class="btns settings-btns">
        <div class="settings">
        <div class="ttip">
        <div class="email-notification-settings">
        <ul>

        <li data-setting-id="lecture_notification" data-setting-val="on">
        <label>New lecture announcement: </label>
        <div class="switcher on">
        <span>on</span>
        <span>off</span>
        <div></div>
        </div>
        </li>
        <li data-setting-id="live_session_invitation" data-setting-val="on">
        <label>Livesession invitation: </label>
        <div class="switcher on">
        <span>on</span>
        <span>off</span>
        <div></div>
        </div>
        </li>
        <li data-setting-id="new_course_announcement" data-setting-val="on">
        <label>New course announcement: </label>
        <div class="switcher on">
        <span>on</span>
        <span>off</span>
        <div></div>
        </div>
        </li>
        <li data-setting-id="new_question" data-setting-val="off">
        <label>New question: </label>
        <div class="switcher off">
        <span>on</span>
        <span>off</span>
        <div></div>
        </div>
        </li>

        </ul>
        <span class="disabled-email-notification off">
        <span>
        You disabled all your email notifications!<br>
        <a class="u-btn smaller" href="https://www.udemy.com/user/edit-notifications">Update your settings</a>
        </span>
        </span>
        </div>

        <ul>
        <li class="unsubscribe-course-link" data-unsubcribe-confirm-text="You will be unsubscribed from this course. Are you sure?">
        <label>Unsubscribe from this course</label>
        </li>
        </ul>

        </div>
        </div>
        </div>-->
        <div class="btns share-btns">
            <!--<div class="share mini-tooltip down">
                Share
                <div class="tooltip-content three-item">
                    <a class="f" data-w="640" data-h="370" href="http://www.facebook.com/sharer.php?t=How to Prototype Web and Mobile Apps in 30 Minutes&amp;u=https://www.udemy.com/prototyping-web-and-mobile-apps/">facebook</a>
                    <a class="t" href="http://twitter.com/intent/tweet?text=How to Prototype Web and Mobile Apps in 30 Minutes&amp;url=https://www.udemy.com/prototyping-web-and-mobile-apps/&amp;via=udemy">twitter</a>
                    <a class="m ud-popup" data-enableloader="true" data-width="600" data-autosize="false" data-wrapcss="static-content-wrapper" href="/share/invite-people?courseId=6446">mail</a>
                </div>
            </div>-->
            {if !empty($blog_url)}
            <a href="{$blog_url->out(false)}" class="blogs" title="{get_string('blogscourse','blog')}"></a>
            {/if}
            <a href="{$course_user_url->out(false)}" class="subs" title="{get_string('participants')}">{$course_overview_info->enrolment_manager->get_total_users()}</a>
            <a href="#dashboard-course-popup-content" class="fancybox info" title="{get_string('coursesummary')}"></a>
        </div>

    </div>
    <div id="dashboard-course-popup-content" style="overflow: hidden; display:none;">
        <h1>{get_string('coursesummary')}</h1>
        <div class="mask">
          <div class="desc">
            <div class="w3c-default">
                {$course_overview_info->summary}
            </div>
          </div>
          <ul class="cat-tag">
            <li>
              <h3>{get_string('category')}:</h3>
              <div>{$course_overview_info->course_category}</div>
            </li>
          </ul>
          
        </div>

    </div>


</div>