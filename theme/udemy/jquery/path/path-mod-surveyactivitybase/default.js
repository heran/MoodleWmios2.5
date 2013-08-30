$(function(){
    $('.left-col').removeClass('left-col');
    $('.right-col').remove();

    //选项切换
    $('.path-mod-surveyactivitybase .search_wd a').each(function(i,n){
        $(n).click(function(){
            $('.path-mod-surveyactivitybase .search_wd a').removeClass("weidu_cur");
            $(n).addClass("weidu_cur");
        });

    });

    /*重新加载页面*/
    $('.path-mod-surveyactivitybase #form1 select').change(function(){

           var values =  $(this).val();
           var url = $('.path-mod-surveyactivitybase #form1').attr('action');
           window.location.assign(url+"&dim2="+values);

    });

});


