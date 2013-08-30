$( function(){
    $('.path-mod-quiz input[type=submit]').addClass('btn');
    $('.path-mod-quiz .submitbtns a').addClass('btn');
    $('.path-mod-quiz table.quizattemptsummary')
        .width($('.path-mod-quiz table.quizattemptsummary').width()-20);
    $('.path-mod-quiz table.quizsummaryofattempt')
        .width($('.path-mod-quiz table.quizsummaryofattempt').width()-20);
    $('.path-mod-quiz #feedback')
        .width($('.path-mod-quiz #feedback').width()-20);

    //navblock

    $('.path-mod-quiz #mod_quiz_navblock .othernav a').addClass('btn');
    (function(){
        var only_nav_block = $('.path-mod-quiz #mod_quiz_navblock:only-child');
        if(!only_nav_block)return;

        var original_top = $(only_nav_block).offset().top;
        var parent_width = $(only_nav_block).parent().width();
        $(window).scroll(function(){
            if($(window).scrollTop()>original_top){
                $(only_nav_block).css({position:'fixed',top:'10px'}).width(parent_width);
            }else{
                $(only_nav_block).css({width:'auto',top:'auto', position:'static'});
            }
        });
    })();


});