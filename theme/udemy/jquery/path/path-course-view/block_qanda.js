//question and answer
$(function(){

    var modInfo = {cmid:0,modname:null,courseid:$('body').attr('courseid')};
    var inModview = false;
    var questionRedactor = {};

    /**
    * Refresh question list 
    * 
    * @param boxId the list's box id
    * @param cmid course module
    * @param courseid course
    * @param position
    */
    var refreshQuestionList = function(boxId,cmid,courseid,position){
        var url = $('#'+boxId).attr('url');
        if(url){
            $.get(url,{cmid:cmid,courseid:courseid,position:position},function(results){
                if(results.status){
                    $('#'+boxId).html('');
                    $.each(results.list,function(i,n){
                        var cminfo = '';
                        if(n.cmid-0>0 ){
                            var cm = results.cms[n.cmid];
                            if(!inModview){
                                if(M.W.modview.modhandler[cm.modname].support.goto_and_play){
                                    cminfo = '<span class="count">'+cm.name+'&nbsp;&nbsp;&nbsp;&nbsp;<b>'+n.position_str+'</b></span>';
                                }else{
                                    cminfo = '<span class="count">'+cm.name+'</span>';
                                }
                            }else if(M.W.modview.modhandler[cm.modname].support.goto_and_play){
                                cminfo = '<span class="count"><b>'+n.position_str+'</b></span>';
                            }
                        }
                        $('#'+boxId).append(
                            '<li class="question">'+
                                '<a href="#" qandaid="'+n.id+'">'+
                                    '<span class="title">'+n.title+'</span>'+
                                    '<span class="details">'+
                                    '<span class="count answer-count">'+n.answers_str+'</span>'+cminfo+
                                    '<span class="more">'+
                                    '<span class="user-title ellipsis">'+results.users[n.userid].fullname+'</span>'+
                                    '<span>'+n.asked_time+'</span>'+
                                    '</span>'+
                                    '</span>'+
                                '</a>'+
                            '</li>');
                    });
                }
            });
        }
    }

    /**
    * Refresh answer
    * 
    * @param boxId
    * @param qandaid
    * @param courseid
    */
    var refreshAnswerList = function(boxId,qandaid,courseid){
        var url = $('#'+boxId).attr('url');
        if(url){
            $.get(url,{qandaid:qandaid,courseid:courseid},function(results){
                if(results.status){
                    var positionInfo = '';
                    if( results.question.cmid-0>0 && M.W.modview.modhandler[results.cm.modname].support.goto_and_play){
                        var cmname = '';
                        if(!inModview){
                            cmname = results.cm.name+'&nbsp;&nbsp;';
                        }
                        positionInfo = '<b class="goto-and-play-mod" modname="'+results.cm.modname+'" position="'+results.question.position+'" cmid="'+results.question.cmid+'">'
                        +cmname+results.question.position_str+'</b><br />';
                    }
                    $('#'+boxId).html('');
                    $('#'+boxId).parents('.single-question')
                    .find('.responses-wrapper article .question-title')
                    .html(positionInfo + results.question.title);

                    $('#'+boxId).parents('.single-question')
                    .find('.responses-wrapper article .question-content').html(results.question.content);
                    $('#'+boxId).parents('.single-question').find('.asker').html(results.quser.who_asks);
                    $.each(results.list,function(i,n){
                        var user = results.users[n.userid];
                        $('#'+boxId).append(
                            '<li class="vote">'+
                            '<div class="top">'+
                            '<span class="thumb" style="background-image: url('+user.userpicture+')"></span>'+
                            '<a class="user" href="#">'+user.fullname+'</a>'+
                            '<time>'+n.asked_time+'</time>'+
                            '</div>'+
                            '<article class="answer-content">'+n.answer+'</article>'+
                            '</li>');
                    });
                }
            });
        }
    }

    //First refresh
    refreshQuestionList('questions-list',0,$('body').attr('courseid'),0);

    $(document)
    .on('modviewOpen',function(e,mod){
        modInfo.cmid = mod.cmid;
        modInfo.modname = mod.modname;
        inModview = true;

        //Move Html's
        $('.questions-list').html('');
        var qNode = $('.block_qanda.block>.content>div#questions');
        $('.ud-coursetaking.wrapper .questions-placeholder').append(qNode);
        refreshQuestionList('questions-list',modInfo.cmid,$('body').attr('courseid'),0);
    })
    .on('modviewClose',function(){

        modInfo.cmid = 0;
        modInfo.modname = null;
        inModview = false;

        $('.questions-list').html('');        
        var qNode = $('.ud-coursetaking.wrapper .questions-placeholder>div#questions');        
        $('.block_qanda.block>.content').append(qNode);
        refreshQuestionList('questions-list',0,$('body').attr('courseid'),0);
    })


    //Editor
    .on('focus','.ud-question-input',function(){
        var editorKey = $(this).parents('form').attr('id');
        $(this).parent().siblings('.form-item-details').show();
        $(this).parent().siblings('.bottom').removeClass('none');
        $(this).parents('form').siblings('.questions-list').css({top:'242px'});
        if(typeof questionRedactor[editorKey] == 'undefined' || !questionRedactor[editorKey]){
            questionRedactor[editorKey] = $(this).parents('form').find('.ud-question-content').redactor({
                buttons:["bold","italic","deleted","|","unorderedlist","orderedlist","|","link"]
            });
            questionRedactor[editorKey].setCode('');            
            $('.redactor_ud-question-content').css({height:'100px'});
        }
    })
    .on('submit','.create-question-form',function(e){
        e.preventDefault();

        var question = {};          
        question.title = $('.ud-question-input').val();  
        if(question.title == ''){
            return;
        }

        editorKey = $(this).attr('id');
        if(typeof questionRedactor[editorKey] == 'undefined' || !questionRedactor[editorKey]){            
            question.content = '';
        }else{
            question.content = questionRedactor[editorKey].getCode();
        }
        if(question.content == ''){
            return;
        }


        question.cmid = modInfo.cmid;
        question.modname = modInfo.modname;
        question.courseid = $('body').attr('courseid');

        if(inModview && M.W.modview.modhandler[modInfo.modname].support.get_now_position ){
            question.position = M.W.modview.modhandler[modInfo.modname].get_now_position(modInfo.cmid);                
        }else{
            question.position = 0;
        }
        if(question.position<0){
            question.position = 0;
        }

        var url = $(this).attr('action');
        var questionForm = this;
        $.post(url,question,function(result){
            if(result.status){
                $(questionForm).find('.cancel').click(); 
                refreshQuestionList(
                    $(questionForm).siblings('.questions-list').attr('id'),
                    question.cmid,
                    question.courseid,
                    0);
            }
        });
    })
    .on('click','.create-question-form .cancel',function(e){
        e.preventDefault();
        var questionForm = $(this).parents('form');

        editorKey = $(questionForm).attr('id');
        if(typeof questionRedactor[editorKey] != 'undefined' && questionRedactor[editorKey]){            
            questionRedactor[editorKey].setCode('');
        }
        $(questionForm).find('.ud-question-input').val('');

        $(questionForm).find('.form-item-details').hide();
        $(questionForm).find('.bottom').addClass('none');
        $(questionForm).siblings('.questions-list').css({top:'54px'});
    })


    //question answer
    .on('click','.questions-list>.question>a',function(e){
        e.preventDefault();        
        $(this).parents('.questions-wrapper').addClass('detail-view');
        var answerForm = $('.ud-questionanswer').find('.create-answer-form');
        editorKey = $(answerForm).attr('id');
        if(typeof questionRedactor[editorKey] == 'undefined' || !questionRedactor[editorKey]){            
            questionRedactor[editorKey] = $(answerForm).find('.ud-question-answer-content').redactor({
                buttons:["bold","italic","deleted","|","unorderedlist","orderedlist","|","link"]
            });
            questionRedactor[editorKey].setCode('');
        }        
        var answerForm = $('.ud-questionanswer').find('.create-answer-form');
        var qandaid = $(this).attr('qandaid');
        var courseid = $('body').attr('courseid');
        $(answerForm).attr('qandaid',qandaid);
        refreshAnswerList($('.questions-wrapper').find('.answers-list').attr('id'),qandaid,courseid);
    })
    .on('submit','.create-answer-form',function(e){
        e.preventDefault();
        var answerForm = this;
        var answer = {};
        editorKey = $(this).attr('id');
        if(typeof questionRedactor[editorKey] == 'undefined' || !questionRedactor[editorKey]){            
            answer.answer = '';
        }else{
            answer.answer = questionRedactor[editorKey].getCode();
        }
        if(answer.answer == ''){
            return;
        }
        answer.courseid = $('body').attr('courseid');
        answer.qandaid = $(this).attr('qandaid');
        var url = $(this).attr('action');
        var me = this;
        $.post(url,answer,function(result){
            if(result.status){
                questionRedactor[editorKey].setCode('');
                refreshAnswerList(
                    $('.questions-wrapper').find('.answers-list').attr('id'),
                    answer.qandaid,
                    answer.courseid);
                var questionItem = $(me).parents('.questions-wrapper')
                .find('li>a[qandaid='+answer.qandaid +']');
                var answerCount = $(questionItem).find('.answer-count b').text()-0+1;
                $(questionItem).find('.answer-count b').text(answerCount);
            }
        });

    })
    .on('click','.single-question .backto',function(e){
        e.preventDefault();        
        $(this).parents('.questions-wrapper').removeClass('detail-view');
    });



});