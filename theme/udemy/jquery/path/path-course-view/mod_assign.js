var M = M || {};
var multimedia_mod_view = {};
M.W = M.W || {};
M.W.modview = M.W.modview || {};
M.W.modview.modhandler = M.W.modview.modhandler || {};


M.W.modview.modhandler.assign = assign_mod_view = {
    support:{goto_and_play:true,get_now_position:true,resume_mod:true},
    preference:{close_side_bar:true, full_screen:false},
    load_modview :function(cmid,content_box,intro_box,callback){
        var url=M.W.modview.currentmod.url;
        var first = true;
        $('<iframe></iframe>')
        .attr('id','assign-modal-iframe')
        .attr('src',M.W.modview.currentmod.url)
        .load(function(e){
            $(e.target).show().addClass('show')
            .contents().find('body').addClass('assign-in-modal');
            if(first){
                callback(cmid);
                first = false;
            }
            M.W.modview.hide_loading();
            $(window.frames["assign-modal-iframe"]).unload(function(e){
                M.W.modview.show_loading();
                $('#assign-modal-iframe').removeClass('show').hide();
            });
        })
        .appendTo($(content_box))
        .hide();
        
        
    }
}