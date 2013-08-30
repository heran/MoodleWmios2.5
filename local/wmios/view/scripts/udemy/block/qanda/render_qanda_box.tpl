<div id="questions" class="ud-questionanswer">
    <div id="questions-wrapper" class="questions-wrapper">
        <div id="questions-mask">
            <form id="create-question-form" name="create-question-form" action="{$url->ask_question}" method="post" class="single-line-form create-question-form">
                <input type="hidden" name="isSubmitted" value="1">
                <span id="show-desc" class="holder"></span>
                <div class="form-item-title">
                    <textarea id="question-title" class="ud-form ud-question-input  ui-autocomplete-input" name="title" maxlength="240" placeholder="{$str->type_your_question}" autocomplete="off"></textarea>
                </div>
                <div class="form-item-details">
                    <textarea id="question-content" class="ud-question-content ud-form ud-wysiwyg " name="content" placeholder="Add more details about your question" rows="8"></textarea>
                </div>
                <div class="bottom none"> <a class="btn-link float-right" target="_blank" href="">Report Technical Issue</a>
                    <input type="submit" value="Ask">
                <a class="cancel" href="#">Cancel</a> </div>
            </form>
            <ul id="questions-list" class="questions-list" url="{$url->get_question_list}">                
            </ul>
            <div class="ajax-loader-stick none" style="margin-top:10px;"></div>
            <div class="load-more none" style="text-align:center; margin: 10px 0;"> <a class="load-more btn">Load More…</a> </div>
        </div>
        <div id="single-question" class="single-question">
            <div class="header"> <a class="backto back-btn2" href="#back">{$str->back}</a>
                <h4 class="ellipsis asker"> </h4>
                <!--<button class="follow" href="#">{$str->follow}</button>-->
            </div>
            <div id="responses-wrapper" class="responses-wrapper">
                <div id="question">
                    <div>
                        <article>
                            <h2 class="question-title"></h2>
                            <div class="question-content"></div>
                        </article>
                    </div>
                </div>
                <div id="responses">
                    <ul id="answers-list" class="answers-list" url="{$url->get_answer_list}">

                    </ul>
                    <div class="answer-box">
                        <form id="create-answer-form" name="create-answer-form" action="{$url->add_question_answer}" method="post" class="single-line-form create-answer-form" qandaid="">
                            <span class="holder" id="show-desc"></span>
                            <textarea name="answer" class="ud-question-answer-content"></textarea>
                            <div class="bottom">
                                <input type="submit" value="Add Answer">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="ajax-loader-stick none" style="margin-top:10px;"></div>
            </div>
        </div>
    </div>
</div>