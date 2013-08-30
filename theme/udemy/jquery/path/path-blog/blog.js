//Path /theme/udemy/javascript/must/blog.js
$(function(){
    var blogEnties = $('.forumpost.blog_entry');
    var inEntry = false, inCourse = false;
    var entryid = $.getUrlVar('entryid')-0;
    var courseid = $.getUrlVar('courseid')-0;

    if(entryid>0){
        inEntry = true;
    }
    if(courseid>0){
        inCourse = true;
    }

    $(blogEnties).each(function(blogI,blogEnty){
        var line =
        '<article class="feed-item" id="[[BLOGID]]">'+
        '<div class="see-more-arrow-box">'+
        '<div class="see-more-arrow">'+
        '<div class="see-more-arrow-to"></div>'+
        '</div>'+
        '</div>'+
        '<div class="portrait-box"><a title="[[USERNAME]]" href="[[USERURL]]" class="feed-portrait-wraper" target="_blank"><img class="feed-portrait" src="[[USERPHOTOURL]]" alt="[[USERNAME]]"></a></div>'+
        '<div class="feed-detail-content-wraper">'+
        '<div class="feed-detail-info-box">'+
        '<div class="feed-detail-info-wraper">'+
        '<div class="arrow-wraper">'+
        '<div class="arrow-border"></div>'+
        '<div class="arrow-bg"></div>'+
        '</div>'+
        '<div class="feed-head-box clearfix">'+
        '<div class="feed-hdinfo-box">'+
        '<div class="feed-private-box hide">[[WHOCANSEE]]</div>'+
        '<div class="feed-user-name clearfix"><a href="[[USERURL]]" class="hb-username left" target="_blank">[[USERNAME]]</a></div>'+
        '</div>'+
        '<span class="feed-time-box" data-timestamp="1368333781">[[BLOGTIME]]</span></div>'+
        '<div class="feed-main-box">'+
        '<div class="feed-title-box clearfix "><a href="[[BLOGURL]]" class="feed-title" target="_blank">[[BLOGTITLE]]</a></div>'+
        '<div class="feed-content clearfix">'+
        '<div class="cnt-text clearfix">'+
        '[[BLOGIMG]]'+
        '<div>[[BLOGCONTENT]]</div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="feed-bottom-box">'+
        '<div class="feed-readall-box clearfix "><a href="[[BLOGURL]]" class="read-all-box clearfix" target="_blank"><span class="a-read-all-tip">继续阅读</span><span class="a-read-all-arrow"></span></a></div>'+
        '<div class="feed-act-box clearfix ">'+
        '<div class="feed-tag-box"><span class="t-tag-label hide">标签：</span>[[TAGS]]</div>'+
        '<div class="feed-control-box">[[BUTTONS]]</div>'+
        '</div>'+
        '<div class="feed-from-box time-box clearfix">[[CHANGETIME]]</div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '</article>';
        line = line.replace(/\[\[USERPHOTOURL\]\]/g,$(blogEnty).find('.picture>a>img').attr('src'));
        line = line.replace(/\[\[USERNAME\]\]/g,$(blogEnty).find('.author>a').text());
        line = line.replace(/\[\[USERURL\]\]/g,$(blogEnty).find('.author>a').attr('href'));
        line = line.replace(/\[\[WHOCANSEE\]\]/g,$(blogEnty).find('.audience').text());
        line = line.replace(/\[\[BLOGTIME\]\]/g,$(blogEnty).find('.author').html().substr($(blogEnty).find('.author').html().search(/<\/a>/)));
        line = line.replace(/\[\[BLOGTITLE\]\]/g,$(blogEnty).find('.subject>a').text());

        var blogContent = $($(blogEnty).find('.content>.no-overflow').get(0));
        var blogImg = $(blogEnty).find('.attachedimages>img');
        var blogInImgs = $(blogContent).find('img');
        if(blogImg.length>0 || blogInImgs.length>0){
            var imgs = $.merge( $.merge([],blogImg), blogInImgs);
            var blogImgStr = $('<div class="text-img-wraper"></div>');
            if(inEntry){
                var blogImgStr = $('<div class="blog-attach-image-box"></div>');
            }
            $(imgs).each(function(imgI,n){
                $(blogImgStr).append('<a rel="fancybox-thumb-'+blogI+'" class="fancybox-thumb" href="'+$(n).attr('src')+'" title="'+$(n).attr('alt')+'" ><img src="'+$(n).attr('src')+'" alt="'+$(n).attr('alt')+'" /></a>');
            });

            line = line.replace(/\[\[BLOGIMG\]\]/g,$('<p></p>').append(blogImgStr).html());
        }else{
            line = line.replace(/\[\[BLOGIMG\]\]/g,'');
        }
        if(!inEntry){
            $(blogContent).find('img').remove();//remove images
            $(blogContent).find('object').remove();//remove flash
        }
        line = line.replace(/\[\[BLOGCONTENT\]\]/g,$(blogContent).html());



        var commands = $('<p></p>');
        $(blogEnty).find('.commands>a').each(function(i,n){
            $(commands).append($(n).clone().addClass('a-control-item'));
        });
        line = line.replace(/\[\[BUTTONS\]\]/g,$(commands).html());

        line = line.replace(/\[\[BLOGURL\]\]/g,$(blogEnty).find('.subject>a').attr('href'));

        var tags = $(blogEnty).find('.tags>a:not(.action-icon)');
        var tagStr = '';
        if(false){//tags.length>0
            //We don't like tag now.
            //Come back for a time
            $(tags).each(function(i,n){
                tagStr += $('<p></p>').append($(n).clone().addClass('t-tag')).html();
            });
        }
        var courseStr = $(blogEnty).find('.tags>a.action-icon').parent().html();
        if(typeof courseStr != 'undefined'){
            tagStr += courseStr;
        }
        line = line.replace(/\[\[TAGS\]\]/g,tagStr);



        var timeLine = $(blogEnty).find('.commands~div:not(#cmt-tmpl):not(.audience):not(.no-overflow):not(.tags):not(.mdl-left)');
        if(timeLine.length > 0){
            line = line.replace(/\[\[CHANGETIME\]\]/g,$(timeLine).text());
        }else{
            line = line.replace(/\[\[CHANGETIME\]\]/g,'');
        }

        var commentNode = $(blogEnty).find('.mdl-left').clone(true,true);
        line = $(line);
        $(line).find('.feed-control-box').append(commentNode);
        $(line).find('.feed-act-box').after($(line).find('.feed-control-box .comment-ctrl'));
        $(line).find('.comment-ctrl').prepend('<div class="cmt-border-top"></div><div class="qcmt-arrow-wraper"><div class="qcmt-arrow-border"></div><div class="qcmt-arrow-shadow"></div><div class="qcmt-arrow"></div><div class="qcmt-arrow-inner"></div></div>');

        $(line).attr('id',$(blogEnty).attr('id'));
        if(!blogI){
            $(line).append($('<p></p>').append($('#cmt-tmpl')).html());
        }

        if(inEntry){
            $(line).find('.feed-readall-box').remove();
        }else{
            $(line).find('.feed-content').addClass('not-in-entry');
        }

        $(line).find('.comment-area .fd a').addClass('btn btn-success');



        $(blogEnty).replaceWith(line);




    });


    //Delete Comment Buttons
    $(document).on('mouseup','.comment-delete',function(){
        $('.comment-delete-confirm').css({background:'none',width:'auto'});
        $('.comment-delete-confirm a:first-child').addClass('btn btn-small btn-danger');
        $('.comment-delete-confirm a:last-child').addClass('btn btn-small btn-success');
    })

    //Add blog Button
    $('.path-blog .addbloglink>a').addClass('btn btn-success');
    if(inCourse){
        //There's a bug
        $('.path-blog .addbloglink>a').text('写篇关于此课程的博客');
    }

    //There's another bug
    //Why This is a bug?
    //Ask moodle.org
    var mainTitle = $('.path-blog h2.main').text();
    $('.path-blog h2.main').each(function(i,mainTitle){
        if($(mainTitle).text().search(/this\-\>course/)){
            $(mainTitle).text($(mainTitle).text().replace(/\{\$this\-\>course\}/g,'此课程'));
        }
    })

    //submit blog button
    $('#page-blog-edit .fitem_actionbuttons input[name=submitbutton]').addClass('btn btn-success');
    $('#page-blog-edit .fitem_actionbuttons input[name=cancel]').addClass('btn btn-danger');

    //blog preference
    $('#page-blog-preferences input[name=submitbutton]').addClass('btn btn-success');
    $('#page-blog-preferences input[name=cancel]').addClass('btn btn-danger');
});