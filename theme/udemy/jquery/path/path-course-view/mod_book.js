// 利用插件 AJAX 把图书的页面内容显示在 course/view.php

var M = M || {};
M.W = M.W || {};
M.W.modview = M.W.modview || {};
M.W.modview.modhandler = M.W.modview.modhandler || {};

M.W.modview.modhandler.book =book_mod_view = {
    support:{goto_and_play:false,get_now_position:false,resume_mod:false},
    load_modview:function(cmid,content_box,intro_box,callback){

        var resize=function(){
            var mh=$('.mod-book-content').height();
            var nh=$('.mod-book-content .navtop').height();
            var bh=$('.mod-book-content .navbottom').height();
            var bch=null;
            if($('.mod-book-content .bookcontent .nav').length>0){
                var navh=$('.mod-book-content .bookcontent .nav').height();
                bch=mh-nh-bh-navh-95;

            }else{

                bch=mh-nh-bh-80;

            }
            $('.mod-book-content .bookcontent .generalbox').height(bch);
        }

        $(window).resize(resize);
        
        var ajaxcontent=function(url){
            var first_load = true;
            if($('.book-menu').length>0){
                first_load = false;
            }
            if(!first_load){

                show_loading();
            }

            $.get(url,function(data){
                //replace whole content
                data = $(data); 
                var blockcontent=$(data).find('.block.block_book_toc>.content').html();
                var modcontent=$(data).find('div[role=main]').html();

                var html='<div class="bookcontents">';

                html +='<div class="book-menu">'+blockcontent+'</div>';

                html +='<div class="mod-book-content">'+modcontent+'</div>';

                html +='</div>';


                $(content_box).html(html);

                var h2text=$('.mod-book-content .box.generalbox.book_content h2').text();

                $('.mod-book-content .box.generalbox.book_content h2').remove();

                $('.mod-book-content .navtop').after('<h2>'+h2text+'</h2>');

                $('.mod-book-content h2').after("<hr>");

                $('.mod-book-content .box.generalbox.book_content').wrap('<div class="bookcontent"></div>');

                if($('.mod-book-content .box.generalbox.book_content h3').length>0){

                    var h3text=$('.mod-book-content .box.generalbox.book_content h3').text();

                    $('.mod-book-content .box.generalbox.book_content h3').remove(); 
                    $('.mod-book-content .box.generalbox.book_content').before('<div class="nav"></div>');

                    $('.mod-book-content .bookcontent .nav').html(h3text);

                }

                if(first_load){

                    callback(cmid);   
                }

                resize();

                
            });

        }

        var show_loading = function(){
            $('.mod-book-content').html("");
            $('.bookcontents div.mod-book-content').addClass('loads');
        }

        var ajaxurl=M.cfg.wwwroot+'/mod/book/view.php?id='+cmid;

        ajaxcontent(ajaxurl);

        $(document).on('click','.book_toc_numbered ul a,.navtop a ,.navbottom a',function(e){

            var url=$(this).attr("href");

            if(url.indexOf('course/view.php')<0){

                ajaxcontent(M.cfg.wwwroot+'/mod/book/'+url);  
                e.preventDefault();
            }

        });

    }

} 
