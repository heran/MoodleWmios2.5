<div id="course-taking-page" style="" class="ud-coursetaking wrapper">
    <div class="main"> <a id="go-back" href="#dashboard">{$str->back_to_course}</a>
        <ul id="timeline" style="">
            <li class="on" data-lectureid="37453">
                <div class="prev-lecture" id="prev-lecture-button">
                    <a href=""></a>
                    <span>{$str->previous_lecture}</span>
                </div>
                <div class="top">
                    <span class="ch">SECTION <span id="modal-section-number"></span></span>
                    <span class="le">LECTURE</span> <span class="no" id="modal-lecture-number">1</span>
                    <h1 id="modal-lecture-name"></h1>
                </div>
                <div class="asset-container">
                    <div class="ud-lecture" data-lectureid="37453" data-autoload="false">
                        <div id="lecture-handler" modorder="" class=""></div>
                        <div id="lecture-handler-loading" class=""></div>
                        <div id="lecture-handler-fullscreen" class="none"></div>
                    </div>
                </div>
                <div class="bottom">
                    <a class="resumemod" id="resume-mod"> {$str->resume_mod}</a>
                    <a class="autoplay" id="auto-play"> {$str->auto_play} <span>OFF</span></a>
                    <a class="next-lecture" href="" id="next-lecture-button">{$str->next_lecture}</a>
                    <div class="share mini-tooltip" style="display:none;"> share
                        <div class="tooltip-content">
                            <a class="f" href="" data-h="370" data-w="640">facebook</a>
                            <a class="t" href="">twitter</a>
                        </div>
                    </div>
                    <a href="" class="mark mini-tooltip" style="display:none;">
                        <span class="tooltip-content">
                            <b>Mark as Completed</b>
                            <b>Mark as Uncompleted</b>
                        </span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <div class="sidebar"> <a class="close-sidebar-btn" href=""></a>
        <div class="sidebar-container">
            <input type="radio" name="tabs" id="tab1" checked="checked" class="tab-modinfo">
            {if $has_qanda}
            <input type="radio" name="tabs" id="tab2" class="tab-qa">
            {/if}
            {if $has_notes}
            <input type="radio" name="tabs" id="tab3" class="tab-notes">
            {/if}

            <div class="tab-label-container">
                <ul class="gray-nav">
                    <li class="e">
                        <label for="tab1">{$str->mod_intro}</label>
                    </li>
                    {if $has_qanda}
                    <li class="q">
                        <label for="tab2">{$str->qanda}</label>
                    </li>
                    {/if}
                    {if $has_notes}
                    <li class="n">
                        <label for="tab3">{$str->notes}</label>
                    </li>
                    {/if}
                </ul>
            </div>
            <div class="tab-divs">
                <div id="extras" class="ud-extras for-tab1">
                    <div id="extras-container">
                        <div id="extras-nav" class="mod-intro-box"></div>
                        <div id="extras-side"></div>
                    </div>
                </div>
                {if $has_qanda}
                <div class="questions-placeholder for-tab2"></div>
                {/if}
                {if $has_notes}
                <div class="notes-placeholder"></div>
                {/if}
            </div>
        </div>
    </div>
</div>  