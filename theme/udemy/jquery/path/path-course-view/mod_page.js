//

var M = M || {};
M.W = M.W || {};
M.W.modview = M.W.modview || {};
M.W.modview.modhandler = M.W.modview.modhandler || {};

M.W.modview.modhandler.page =page_mod_view = {
    support:{goto_and_play:false,get_now_position:false,resume_mod:false},
    load_modview:function(cmid,content_box,intro_box,callback){

        $.get(M.cfg.wwwroot+'/mod/page/view.php',{id:cmid},function(data){

            data = $(data);
            var pageh=$(data).find('div[role=main]>#pageintro');
            var title=$(data).find('div[role=main]>#pageheading').text();
            var miaohtml=$(data).find('div[role=main]>#pageintro>div').html();
            var contenthtml=$(data).find('div[role=main]>.box.generalbox>div').html();
            var time=$(data).find('div[role=main]>.modified').text();

            var html =   
            '<div class="mod-page-hcontent">'+
            '<h2 class="pageheading">'+title+'</h2>';
            
            if(pageh.length>0){
            
                html += '<div class="intro">'+miaohtml+'</div>';
            }
            
            html += '<div class="content">'+contenthtml+'</div>'+
            '<div class="htime">'+time+'</div>'+
            '</div>'; 

            $(content_box).html(html);

            if(typeof callback != 'undefined'){
                callback(cmid);
            }
        });

    }



}