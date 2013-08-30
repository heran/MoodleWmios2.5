var M = M || {};
M.W = M.W || {};
M.W.modview = M.W.modview || {};
M.W.modview.currentmod = M.W.modview.currentmod|| {cmid:0,modname:null};

var M_W_modview = M.W.modview;

M.W.modview.init = function(){

    var inModview = false;

    /**
    * We Need find the next mod, prev mod or the max mod number.
    *
    * @type Object
    */
    var findOrder = {
        /**
        * Find the next order
        *
        * @param modorder
        */
        next : function(modorder){
            var nextorder = modorder+1;
            var maxorder = this.max();
            while(nextorder <= maxorder){
                if($('#curriculum .wrapper>ul>li.modalview[modorder="'+nextorder+'"]').length == 0){
                    nextorder += 1;
                }else{
                    break;
                }
            }
            if(nextorder > maxorder){
                nextorder = -1;
            }
            return nextorder;
        },

        max : function(){
            var maxorder = 0;
            $('#curriculum .wrapper li[modorder]').each(function(i,n){
                var theOrder = $(this).attr('modorder')-0;
                if(theOrder>maxorder){
                    maxorder = theOrder;
                }
            });
            return maxorder;
        },

        /**
        * Find the prev order
        *
        * @param modorder
        */
        prev:function(modorder){
            var prevorder = modorder-1;
            while(prevorder>0){
                if($('#curriculum .wrapper>ul>li.modalview[modorder="'+prevorder+'"]').length == 0){
                    prevorder -= 1;
                }else{
                    break;
                }
            }
            return prevorder;
        }

    }

    /**
    * Display the mod that has the same modorder
    * Orders come from the order of the mod in the course view page.
    * @param modorder
    */
    var displayModByModorder = function(modorder){
        $('#curriculum .wrapper>ul>li.modalview[modorder="'+modorder+'"]').click();
        if($('#curriculum .wrapper>ul>li.modalview[modorder="'+modorder+'"]').length == 0){
            return false;
        }else{
            return true;
        }
    }

    /**
    * Display the mod that has the same cmid
    *
    * @param cmid
    */
    var displayModByCmid = function(cmid){
        $('#curriculum .wrapper>ul>li.modalview[cmid="'+cmid+'"]').click();
        if($('#curriculum .wrapper>ul>li.modalview[cmid="'+cmid+'"]').length==0){
            return false;
        }else{
            return true;
        }
    }

    /**
    * Display one modaled mod's view;
    * Clik the mod,then display modal box
    *
    */
    $(document).on('click','#curriculum .wrapper>ul>li.modalview',function(event){
        event.preventDefault();
        event.stopPropagation();
        var modname = $(this).attr('modname');
        var cmid = $(this).attr('cmid');
        var url = M.cfg.wwwroot+'/mod/'+modname+'/view.php';
        var param = {id:cmid,wt:'xml'};
        M_W_modview.currentmod = M_W_modview.currentmod|| {};
        M_W_modview.currentmod.cmid = cmid;
        M_W_modview.currentmod.modname = modname;
        M_W_modview.currentmod.name = $(this).find('h6>a').text();
        M_W_modview.currentmod.url = $(this).find('h6>a').attr('href');
        M_W_modview.currentmod.modhandler = M_W_modview.modhandler[modname]
        ? M_W_modview.modhandler[modname] : null;

        var modorder = $(this).attr('modorder')-0;

        $('#modal-section-number').html($(this).parent('ul').prev('h5').attr('sectionnumber'));
        $('#modal-lecture-number').html(modorder);
        $('#lecture-handler').attr('modorder',modorder);
        $('#course-taking-page').show().removeClass('off');

        $('#prev-lecture-button').show();
        $('#next-lecture-button').show();
        $('#curriculum>.list').hide();
        $('#region-post').hide();

        if(modorder<=0){
            return
        }else{

            //Hide prev button
            if(findOrder.prev(modorder)<=0){
                $('#prev-lecture-button').hide();
            }

            //Hide next button
            if(findOrder.next(modorder)<=0){
                $('#next-lecture-button').hide();
            }

        }

        var content_box = $('#lecture-handler');
        var intro_box = $('.mod-intro-box');
        //I'm laoding the mod's content
        $(document).trigger('modviewLoading',[{url:url,cmid:cmid,modname:modname,content_box:content_box,intro_box:intro_box}]);

        //We need loading....
        M_W_modview.show_loading();

        if(M_W_modview.currentmod.modhandler && M_W_modview.currentmod.modhandler.load_modview){
            //Load by itself.
            M_W_modview.currentmod.modhandler.load_modview( cmid,$('#lecture-handler'),$('.mod-intro-box') , M_W_modview.loaded);
        }else{
            //Load by me.
            $.get(url,{id:cmid,wt:'xml'},function(data){
                //Mod process the html data.
                if(M_W_modview.currentmod.modhandler && M_W_modview.currentmod.modhandler.preprocess_moddata){
                    data = M_W_modview.currentmod.modhandler.preprocess_moddata(data);
                }
                $(content_box).html(data);
                $(intro_box).html($('.mod-intro[cmid="'+cmid+'"]').html());
                M_W_modview.loaded(cmid);
            });
        }



        //trigger event.
        $(document).trigger('modviewOpen',[{cmid:cmid,modname:modname}]);

        inModview = true;

        return false;
    });

    /**
    * display the previous mod
    *
    */
    $(document).on('click','#prev-lecture-button',function(){
        var modorder = $('#lecture-handler').attr('modorder')-0;
        displayModByModorder(findOrder.prev(modorder));

    });

    /**
    * display the next mod
    *
    */
    $(document).on('click','#next-lecture-button',function(){
        var modorder = $('#lecture-handler').attr('modorder')-0;
        displayModByModorder(findOrder.next(modorder));
    });

    /**
    *   Close the side bar.
    * Close the introduction question_and_answer notes.
    *
    */
    $(document).on('click','#course-taking-page .close-sidebar-btn',function(){
        $('#course-taking-page').toggleClass('off');
        return false;
    });

    /**
    * close side bar
    *
    */
    M_W_modview.close_side_bar = function(){
        $('#course-taking-page .close-sidebar-btn').click();
    }

    /**
    * Continue study one mod from last time.
    *
    */
    $(document).on('click','#resume-mod',function(e){
        if(M_W_modview.modhandler[M_W_modview.currentmod.modname].support.resume_mod){
            M_W_modview.modhandler[M_W_modview.currentmod.modname].resume_mod(M_W_modview.currentmod.cmid);
        }
        $(this).hide();
        e.preventDefault();
        e.stopPropagation();
    });

    /**
    * return to course main page.
    *
    */
    $(document).on('click','#course-taking-page #go-back',function(){
        $('#lecture-handler').html('');
        $('#course-taking-page').hide();
        $('#curriculum>.list').show();
        $('#region-post').show();
        $(document).trigger('modviewClose',{cmid:M_W_modview.currentmod.cmid,modname:M_W_modview.currentmod.modname});
        inModview = false;
        M.W.modview.currentmod = {};
        return false;
    });

    /**
    * Button Display a mod by the cmid.
    *
    */
    $(document).on('click','.display-by-cmid',function(e){
        if(displayModByCmid($(this).attr('cmid'))){
            e.preventDefault();
            e.stopPropagation();
        }
    });

    /**
    * Button. Goto and play one mod.
    * If in modal view,Resume mod.
    * Else it has cmid, call displayModByCmid.
    *
    *
    */
    $(document).on('click','.goto-and-play-mod',function(e){
        var w = this;
        var cmid = $(this).attr('cmid')-0;
        var modname = $(this).attr('modname');
        function play(){
            if( M_W_modview.modhandler[modname].support.goto_and_play){
                M_W_modview.modhandler[modname].goto_and_play(cmid,$(w).attr('position'));
                return true;
            }
            return false;
        }
        if(inModview){
            if(play()){
                e.preventDefault();
                e.stopPropagation();
            }
        }else if(cmid){
            if(displayModByCmid(cmid)){
                e.preventDefault();
                e.stopPropagation();
            }
        }

    });

    /**
    * Resize buttons
    */
    (function(){
        var move_timer = 0;
        $(document)
        .on('mouseover','#course-taking-page .ud-lecture',function(e){
            if(!M_W_modview.currentmod.modhandler.support ||
                !M_W_modview.currentmod.modhandler.support.full_screen){
                return;
            }
            $('#lecture-handler-fullscreen').removeClass('none');
        })
        .on('mouseout','#course-taking-page .ud-lecture',function(e){
            if(!M_W_modview.currentmod.modhandler.support ||
                !M_W_modview.currentmod.modhandler.support.full_screen){
                return;
            }
            $('#lecture-handler-fullscreen').addClass('none');
        })
        .on('click','#course-taking-page .ud-lecture #lecture-handler-fullscreen',function(e){
            if(!M_W_modview.currentmod.modhandler.support ||
                !M_W_modview.currentmod.modhandler.support.full_screen){
                return;
            }
            if($(this).hasClass('resize-small')){
                $(this).removeClass('resize-small');
                $(this).parents('.ud-lecture').removeClass('full-screen');
            }else{
                $(this).addClass('resize-small');
                $(this).parents('.ud-lecture').addClass('full-screen');
            }
        });
        /**
        * Manualy clik resize button
        * full screen
        */
        M_W_modview.resize_full = function(){
            $('#course-taking-page .ud-lecture #lecture-handler-fullscreen').removeClass('resize-small').click();
        }

        /**
        * Manualy clik resize button
        * small screen
        */
        M_W_modview.resize_small = function(){
            $('#course-taking-page .ud-lecture #lecture-handler-fullscreen').addClass('resize-small').click();
        }
    })();




    //May cmid in the URL GET Params.
    (function(){
        var cmid = $.getUrlVar('cmid')-0;
        displayModByCmid(cmid);
    })();

}

/**
* After one mod's ratio is updated.
* We need update the Ratio Circle's ratio.
*
*/
M.W.modview.update_completion_ration = function(cmid,ratio){
    $('#curriculum .wrapper>ul>li.modalview[cmid="'+cmid+'"] .circle span').css({width:ratio});
}

/**
* When get content from server, Show a loading image.
*
*/
M_W_modview.show_loading = function(){
    $('#lecture-handler-loading').addClass('loading');
}

/**
* When get content from server, Hide a loading image.
*
*/
M_W_modview.hide_loading = function(){
    $('#lecture-handler-loading').removeClass('loading');
}

/**
* after mod view laoded
*
*/
M_W_modview.loaded = function(cmid){

    //Maybe handler don't like side bar.Why? I don't know.But Closing side bar gets more space.
    if(M_W_modview.currentmod.modhandler.preference &&
        M_W_modview.currentmod.modhandler.preference.close_side_bar){
        M_W_modview.close_side_bar();
    }
    //Need full screen?
    if(M_W_modview.currentmod.modhandler.preference &&
        M_W_modview.currentmod.modhandler.preference.full_screen &&
        M_W_modview.currentmod.modhandler.support &&
        M_W_modview.currentmod.modhandler.support.full_screen){
        M_W_modview.resize_full();
    }
    //We remove loading....
    M_W_modview.hide_loading();
};

$(document).ready(M.W.modview.init);