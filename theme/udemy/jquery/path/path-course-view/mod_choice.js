// 利用插件 AJAX 把投票的页面内容显示在 course/view.php

var M = M || {};
M.W = M.W || {};
M.W.modview = M.W.modview || {};
M.W.modview.modhandler = M.W.modview.modhandler || {};

M.W.modview.modhandler.choice =choice_mod_view = {
    support:{goto_and_play:false,get_now_position:false,resume_mod:false},
    load_modview:function(cmid,content_box,intro_box,callback){

        $.get(M.cfg.wwwroot+'/mod/choice/view.php',{id:cmid},function(data){

            data = $(data); 

            var content=$(data).find('div[role=main]').html();

            var html='<div class="choice-content">';
            html +='<div class="contentheader">'+M.W.modview.currentmod.name+'</div>';
            html +='<div class="mod-choice-content">'+content+'</div>';
            html +='</div>';

            $(content_box).html(html);

            $('.mod-choice-content>h2').wrap('<div class="contenth2"></div>');
            $('.mod-choice-content #attemptsform').wrap('<div class="sform"></div>');

            $('.mod-choice-content #attemptsform #attempt-user4').hide();
            $('.mod-choice-content .attemptaction #attempt-user4').hide();

            $('.mod-choice-content input[type=submit]').addClass('btn');
            $('.mod-choice-content input[type=submit]').addClass('btn-success');
            $('.mod-choice-content input[type=submit]').addClass('btn-small');

            $('.mod-choice-content table.results.names.boxaligncenter .user a').attr('target','_blank');

            $('.mod-choice-content ul.choices li').find('input[name=answer]').css('display','none');


            if(typeof callback != 'undefined'){
                callback(cmid);
            }
        });



    }



}

$(function(){
    //callback
    function ajaxsubmit(e){

        var val=$('input[name=answer]:checked').val();

        if(val==null){

            return false;

        }else{

            var url=$('.mod-choice-content form').attr('action');

            $.post(url,$('.mod-choice-content form').serialize(),function(data){

                data = $(data); 

                var content=$(data).find('div[role=main]').html();

                $('.mod-choice-content').html(content); 

                $('.mod-choice-content table.results.names.boxaligncenter .user a').attr('target','_blank');

                $('.mod-choice-content .attemptaction #attempt-user4').hide();
            }); 
        }
        e.preventDefault();


    }

    //利用ajax刷新保存投票选项
    $(document).on('submit','.mod-choice-content form',ajaxsubmit);

    $(document).on('click','.mod-choice-content ul.choices li',function(){

        $('.mod-choice-content ul.choices li').removeClass('options');
        $(this).addClass('options');
        $(this).find('input[name=answer]').attr('checked','checked');
    });



});