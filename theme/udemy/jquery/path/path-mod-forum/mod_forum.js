/*path /theme/udemy/js/must/mod_form.jss*/
$(function(){
    //Add button's style
    $('.path-mod-forum .singlebutton.forumaddnew input[type=submit]').addClass('btn btn-success');

    //thread list
    (function(){
        var thread_list = $('<ul class="thread-list"></ul>');    
        var replies_str = $('.path-mod-forum .forumheaderlist .header.replies').text();
        var autor_str = $('.path-mod-forum .forumheaderlist .header.author').text();
        var reply_str = $('.path-mod-forum .forumheaderlist .header.lastpost').text();
        var odd_line = true;
        $('.path-mod-forum .forumheaderlist tbody>tr').each(function(i,n){
            var line = '<li class="thread-list-row '+ (odd_line ? 'odd' : 'even')+'">';
            odd_line = !odd_line;
            var replies_num =  $(n).find('.replies>a').text();

            line +=     '<div class="thread-li-left">';
            line +=         '<div class="reply-num" title="'+replies_num+replies_str+'">' + replies_num + '</div>';
            line +=     '</div>';
            line +=     '<div class="thread-li-middle">';
            line +=         '<div class="title" title="'+$(n).find('.topic a').text()+'">';
            line +=             $(n).find('.topic').html()
            line +=         '</div>';
            line +=         '<div class="content" title="'+autor_str+'">';
            line +=         '</div>';
            line +=     '</div>';
            line +=     '<div class="thread-li-right">';
            line +=         '<div class="author" title="'+autor_str+'">';
            line +=             $(n).find('.author').attr('title','').html()
            line +=         '</div>';            
            line +=         '<div class="replyer" title="'+reply_str+'">';
            line +=             '<span class="name">';
            line +=                 $('<p></p>').append($(n).find('.lastpost a:first-of-type')).html()
            line +=             '</span>';
            line +=             '<span class="time">';
            line +=                 $('<p></p>').append($(n).find('.lastpost a:last-of-type')).html()           
            line +=             '</span>';        
            line +=         '</div>';
            line +=     '</div>';
            line +=  '</li>';

            $(thread_list).append(line);
        });
        $('.path-mod-forum .forumheaderlist').replaceWith(thread_list);

        var resize = function(){
            var w = $('.path-mod-forum .thread-list').width();
            var l = $('.path-mod-forum .thread-list .thread-li-left').width();
            var r = $('.path-mod-forum .thread-list .thread-li-right').width();
            $('.path-mod-forum .thread-list .thread-li-middle').width(w-l-r-20);
        }
        resize();
        $(window).resize(resize);

    })();

    //We are going into the thread We Need Forum Title
    (function(){
        var forum_nav = $('.ddown.current-module>ul>li:last-child');
        if(forum_nav.length > 0 && $('#intro').length == 0){
            $('.path-mod-forum #maincontent').after('<div id="intro" class="box generalbox">'
                +$(forum_nav).html()+'</div>');
        }
    })();

    //Discuss
    (function(){
        $('.path-mod-forum .forumpost').each(function(i,n){
            var line = '<div class="forumpost-row">';
            line +=     '<div class="author-box">';
            line +=         '<ul class="p_author">';
            line +=             '<li class="icon">';
            line +=                 '<div class="icon_relative">';
            line +=                     $(n).find('.picture').html();
            line +=                 '</div>';
            line +=             '</li>';
            line +=             '<li class="d_name">';            
            line +=                     $('<p></p>').append($(n).find('.author a').clone()).html();
            line +=             '</li>';
            line +=         '</ul>';
            line +=     '</div>';
            line +=     '<div class="d_post_content_main">';
            line +=         '<div class="p_title">';
            line +=             $(n).find('.subject').html()
            line +=         '</div>';
            line +=         '<div class="p_content">';
            line +=             $(n).find('.content').html()
            line +=         '</div>';
            line +=         '<div class="p_operation">';
            line +=             '<span class="time">';
            line +=                 $(n).find('.author').html().substr($(n).find('.author').html().search(/<\/a>/));
            line +=             '</span>';
            line +=             $(n).find('.commands').html()
            line +=         '</div>';
            line +=     '</div>';
            line += '</div>';
            $(n).replaceWith(line);
        });
        var resize = function(){
            $('.forumpost-row').each(function(i,n){
                var r = $(this).width();
                var w = $(this).find('.author-box').width();
                $(this).find('.d_post_content_main').width(r-w-30);
            });           
        }
        resize();
        $(window).resize(resize);

        $('.path-mod-forum #mformforum .felement.fsubmit input[type=submit]').addClass('btn btn-success');
        $('.path-mod-forum #notice .buttons .singlebutton:first-child input[type=submit]').addClass('btn btn-danger');
        $('.path-mod-forum #notice .buttons .singlebutton:last-child input[type=submit]').addClass('btn btn-success');

    })();

    //advance search
    $('#page-mod-forum-search.path-mod-forum .searchbox input[type=submit]').show().addClass('btn btn-success');

    /**************搜索讨论区样式*************************/
    (function(){
        var type=$(".path-mod-forum .forumsearch #searchforums").attr("type");
        var value=$(".path-mod-forum .forumsearch #searchforums").attr("value");

        $(".path-mod-forum .forumsearch input:eq(1)").remove();

        $(".path-mod-forum .forumsearch .invisiblefieldset").append('<button name="bt"></button>');

        $(".path-mod-forum .forumsearch button[name='bt']").attr("type",type);

        $(".path-mod-forum .forumsearch button[name='bt']").addClass("icon-search");
        $(".path-mod-forum .forumsearch button[name='bt']").addClass("searchform_button");

        $(".path-mod-forum .forumsearch input[name='search']").attr("placeholder",value);
    })();
});