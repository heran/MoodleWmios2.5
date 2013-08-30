var M = M || {};
var multimedia_mod_view = {};
M.W = M.W || {};
M.W.modview = M.W.modview || {};
M.W.modview.modhandler = M.W.modview.modhandler || {};


M.W.modview.modhandler.multimedia = multimedia_mod_view = {
    support:{goto_and_play:true,get_now_position:true,resume_mod:true,full_screen:false},
    preference:{close_side_bar:true,full_screen:false},
    counter:0,
    kdp:null,
    cmid:0,
    type:null,
    kdp:null,
    current:0,
    lastview:0,
    install:function(cmid,lastview,type){
        this.current = 0;
        this.counter = 0;
        this.lastview = lastview;
        this.kdp = $('#cmid'+cmid).find('object').get(0);
        if(!this.kdp)return;
        $(this.kdp).attr('width','100%');
        $(this.kdp).attr('height','100%');
        $(this.kdp).parent().css('height','100%');
        this.cmid = cmid;
        this.type = type;
        this.kdp.addJsListener('playerUpdatePlayhead','completionRatioUpdate');
        this.kdp.addJsListener('playerPlayEnd','completionratioplayend');
        this.kdp.addJsListener('playerSeekEnd','completionratioseekend');
        setTimeout(function(){
            multimedia_mod_view.kdp.sendNotification('doPlay');
            multimedia_mod_view.kdp.sendNotification('doStop');
            },2000);
    },
    uninstall:function(){
        this.cmid = 0;
        this.type = null;
    },
    resume_mod:function(cmid){
        this.goto_and_play(cmid,multimedia_mod_view.lastview,true);
    },
    get_now_position:function(cmid){
        if(multimedia_mod_view.current<0){
            result = 0;
        }else{
            result = multimedia_mod_view.current;
        }
        return result;  
    },
    goto_and_play:function(cmid,gotonum,play){        
        if(!multimedia_mod_view.kdp)return;
        var kdp = multimedia_mod_view.kdp;
        kdp.sendNotification('doSeek', parseFloat(gotonum));
        if(play){
            kdp.sendNotification('doPlay');   
        }
    },
    load_modview :function(cmid,content_box,intro_box,callback){
        $.get(M.cfg.wwwroot+'/mod/multimedia/view.php',{id:cmid,wt:'xml'},function(data){
            $(content_box).html(data);
            $(intro_box).html($('.mod-intro[cmid="'+cmid+'"]').html());
            if(callback){
                callback(cmid);
            }
        });

    }
}




window.completionRatioUpdate = function(data,id){

    var is_end = 'no';
    if(data == -1){
        is_end = 'yes';
        multimedia_mod_view.current =0
    }else{
        multimedia_mod_view.current = data;
    }

    multimedia_mod_view.counter++;

    if((multimedia_mod_view.counter<10 || this.cmid==0) && data != -1 ){
        return;
    }

    $.post('/local/wmios/completion/update.php',{cmid:multimedia_mod_view.cmid,now:data,end:is_end},function(data){
        var result = $.parseJSON(data);
        M.W.modview.update_completion_ration(result.cmid,result.ratio_str);
    });
    multimedia_mod_view.counter = 0;
}
window.completionratioplayend=function(a,b,c,d,e,f){
    completionRatioUpdate(-1,null);
};
window.completionratioseekend=function(a,b,c,d,e,f){
    multimedia_mod_view.counter = 0;
    multimedia_mod_view.kdp.sendNotification('doPlay');
    multimedia_mod_view.counter = 0;
};