var searAttrChange = function(attrKey,attr){
    $(document).on('change', '.exsearch-box-'+attrKey+' select', function(){
        var me = $(this).children('option:selected');
        $(this).parent().find('input').val($(me).val());
        var level=$(me).attr('level');
        var aid=$(me).val();
        this.changed = true;//{* 设置变量用于click和change事件的校验 *}

        var chain = 'attr.children';//{* 跟踪属性变量的深度 *}

        $(this).nextAll('select').remove();

        $(this).parent().find('option:selected').each(function(i,n){
            chain += '.c'+$.trim($(this).val())+'.children';//{* 这里的字符c是为了适应json数据的跟踪  *}
            if($(me).val()==$(this).val())
            {
                var children = false;
                eval('if(typeof '+chain+' != "undefined") { children=eval(chain) }');
                if(!children || (typeof children.length != 'undefined' && children.length==0))
                {
                    return;
                }
                var sel = '';
                var opt = '';
                for(x in children){
                    var child = children[x];
                    opt += '<option level="'+child.level+'" value="'+child.id+'">'+child.content+'</option>';
                };
                sel += '<select>';
                sel += '<option value="0" level="'+$(this).attr('level')+'">所有'+$(this).text()+'</option>';
                sel += opt;
                sel += '</select>';
                $(me).parent().after(sel);
                return;
            }

        });
});
}

$(document).on('click','#exsearch',function(){
    $('.exsearch-box').toggle('fast');
    return false;
});

var editAttrChange = function(attrKey,attr){
    $(document).on('change', 'select.document_fields_change',function()
        {
            var me = $(this).children('option:selected');
            $(this).parents('form').find('input[name="'+attrKey+'"]').val($(me).val());
            var level=$(me).attr('level');
            var aid=$(me).val();
            this.changed = true;

            var chain = 'attr.children';

            $(this).nextAll().remove();

            var parent_div = $(this).parents('.felement.fselect');

            $(parent_div).find('option:selected').each(function(i,n){
                chain += '.c'+$.trim($(this).val())+'.children';
                if($(me).val()==$(this).val())
                {
                    var children = false;
                    eval('if(typeof '+chain+' != "undefined") { children=eval(chain) }');
                    if(!children || (typeof children.length != 'undefined' && children.length==0))
                    {
                        return;
                    }
                    var sel = '';
                    var size = 0;
                    var opt = '';
                    opt += '<option value="'+$(me).val()+'">所有'+$(me).text()+'</option>';
                    for(x in children){
                        size += 1;
                        var child = children[x];
                        opt += '<option level="'+child.level+'" value="'+child.id+'">'+child.content+'</option>';
                    };
                    size = size==1 ? size+1 : size;
                    //sel += '<div class="document_fields_change">';
                    sel += '<select class="document_fields_change">';
                    sel += opt;
                    sel += '</select>';
                    $(parent_div).append(sel);
                    return;
                }

            });
    }).on('click','select.document_fields_change',function(){
        if(typeof this.changed != 'undefined' && !this.changed)
        {
            $(this).change();
        }
        this.changed = false;
    });
}

$(document).on('click','.more-conditions',function(e){
    $(this).parents('.filter-conditions').toggleClass('unwrap');
    e.preventDefault();
})

//filter document list
$(function(){
    function refresh_view_list(url)
    {
        $('.document-entity-list-wrapper').html('<div class="ajax-loader-stick" style="margin-top:30px;"></div>');
        $.get(url+ '&slice=1',function(data){
            $('.document-entity-list-wrapper').html(data);
            $.replaceBrowserUrlWithParams(null,url ,null,null);
        });
    }
    $(document).on('click','.fileter-or',function(e){
        $(this).siblings('.fileter-or').removeClass('on');
        $(this).addClass('on');

        document_view_list_get_params[$(this).attr('field')] = $(this).attr('value');

        var url = M.cfg.wwwroot+'/mod/document/view.php?ajax=1';
        $.each(document_view_list_get_params,function(k,v){
            url+='&'+k+'='+v;
        });
        refresh_view_list(url);


        e.preventDefault();
    })
    .on('click','.document-entity-list-wrapper .paging a',function(e){
        refresh_view_list($(this).attr('href'));
        e.preventDefault();
    }).on('click','.document-entity-list-wrapper .delete-document',function(e){
        if(confirm("是否删除此文档？")){
            var me = this;
            $.get($(this).attr('href'),{goto_list:1},function(){
                $(me).parents('li').remove();
            });
            e.preventDefault();
        }else{

        }
    });

    $('.document-view-wrapper .view-document').fancybox({
        padding:0,
        onUpdate:function(){
            var height = $('.upload-view-main').parents('.fancybox-inner').height()
                -$('.upload-view-doc-header').height()
                -$('.doc-reader-tools').height()-20;

            $('.doc-reader-box iframe').height(height);
        }
    });
})
//
//$(function(){
//    var resize = function(){
//        if($('.document-entity-view-wrapper').length<=0){
//            return ;
//        }
//        var height = $('.upload-view-body').height()
//            -$('.upload-view-doc-header').height()
//            -$('.doc-reader-tools').height()-20;
//        $('.document-entity-view-wrapper .doc-reader-box iframe').height(height);
//    }
//    resize();
//    $(window).resize(resize);
//})