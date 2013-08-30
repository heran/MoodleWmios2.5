//Update notes
$(function(){
    var modInfo = {cmid:0,modname:null,courseid:$('body').attr('courseid')};
    var inModview = false;
    var refreshNoteList = function(boxId,cmid,courseid,position){
        var url = $('#'+boxId).attr('url');
        if(url){
            $.get(url,{cmid:cmid,courseid:courseid,position:position},function(results){
                if(results.status){
                    $('#'+boxId).html('');
                    $.each(results.list,function(i,n){
                        var cmInfo = '';
                        var modname = '';
                        var goto_class = '';
                        var bullet = '';
                        
                        if(n.cmid-0>0){
                            var cm_name = '';
                            var cm = results.cms[n.cmid];
                            if(inModview){
                                cm_name = n.position_str;
                                cmInfo = '<br /><a class="display-by-cmid" cmid="'+cm.id+'" >'+cm.name+'</a>';
                                modname =  'modname="'+cm.modname+'"';
                                if(M.W.modview.modhandler[cm.modname].support.goto_and_play){
                                    cm_name = n.position_str;
                                    goto_class = 'goto-and-play-mod';
                                }else{
                                    cm_name = '';
                                    goto_class = '';
                                }
                            }else{
                                cm_name = n.position_str + '<br />'+cm.name;
                                goto_class = 'display-by-cmid';
                            }
                            bullet = '<div class="bullet ' + goto_class + '" modname="'+cm.modname+'" position="'+n.position+'" cmid="'+n.cmid+'">'+cm_name+'</div>'
                        }else{
                            bullet = '<div class="bullet">$nbsp;</div>';
                        }
                        $('#'+boxId).append(
                            '<li note_id="'+n.id+'">'+
                            bullet+
                            '<p class="ud-inplaceeditor">'+
                            '<span>'+n.text.replace(new RegExp("\r\n","g"),"<br />")
                            .replace(new RegExp("\n","g"),"<br />")
                            .replace(new RegExp("\r","g"),"<br />")+'</span>'+
                            '<span class="inplaceeditor-delete none">×</span>'+
                            '</p>'+
                            '</li>');
                    });
                }
            });
        }
    }
    
    //First Refresh
    refreshNoteList('notes-list',0,$('body').attr('courseid'),0);
    
    //update a note(add or edit)
    $(document).on('keypress','.note-input',function(e){
        if(e.which == 10 && e.ctrlKey){
            var url = $(this).parent('form').attr('action');
            var postData = modInfo;
            postData.text = $(this).val();
            postData.courseid = $('body').attr('courseid');
            var texta = this;
            if(inModview){
                if( M.W.modview.modhandler[modInfo.modname].support.get_now_position){
                    postData.position = M.W.modview.modhandler[modInfo.modname].get_now_position(modInfo.cmid);
                }else{
                    postData.position = 0;
                }
            }
            $.post(url,postData,function(result){
                if(result.status){
                    $(texta).val('');
                    refreshNoteList(
                        $(texta).parents('form').parent().find('.notes-list-box').attr('id'),
                        postData.cmid,
                        $('body').attr('courseid'),
                        0);
                }                    
            });
        }
    })
    .on('modviewOpen',function(e,mod){
        modInfo.cmid = mod.cmid;
        modInfo.modname = mod.modname;
        inModview = true;
        
        $('.notes-list-box').html('');
        var blockHtml = $('.block_notes.block>.content').html();
        $('.block_notes.block>.content').html('');
        $('.ud-coursetaking.wrapper .notes-placeholder').replaceWith(blockHtml);
        
        
        
        refreshNoteList('notes-list',modInfo.cmid,$('body').attr('courseid'),0);

    })
    .on('modviewClose',function(){
        modInfo.cmid = 0;
        modInfo.modname = null;
        inModview = false;
        
        $('.notes-list-box').html('');        
        var blockNode = $('.ud-coursetaking.wrapper #notes').clone();
        $('.ud-coursetaking.wrapper #notes').replaceWith('<div class="notes-placeholder"></div>');
        $('.block_notes.block>.content').append(blockNode);        
        
        refreshNoteList('notes-list',0,$('body').attr('courseid'),0);
    })
    .on('click','.ud-inplaceeditor',function(){
        var p = this;

        if($(this).find('textarea').length>0){
            return;
        }

        var deleteButton = $($(this).find('span').get(1)).clone();            
        var oldHtmlNode = $($(this).find('span').get(0)).clone();
        var oldHtml = $(oldHtmlNode).html();
        var width = $(this).width();
        var height = $(this).height();
        $(this).html('<textarea>'+oldHtml.replace(new RegExp("<br />","g"),"\n").replace(new RegExp("<br>","g"),"\n")+'</textarea>');
        var textarea = $(this).find('textarea');
        $(textarea).width(width);
        $(textarea).height(height);
        $(textarea).focus();
        $(textarea).focusout(function(){
            var newHtml = $(this).val().replace(new RegExp("\r\n","g"),"<br />")
            .replace(new RegExp("\n","g"),"<br />")
            .replace(new RegExp("\r","g"),"<br />");
            $(oldHtmlNode).html(newHtml);
            var note_id = $(this).parents('li').attr('note_id');
            var url = $(this).parents('.ud-notes-box').find('form').attr('action');
            $.post(url,{note_id:note_id,courseid:$('body').attr('courseid'),text:$(this).val()},function(){                    
            });
            $(p).html('').append(oldHtmlNode).append(deleteButton);                
        });
    })
    .on('mouseover','.ud-inplaceeditor',function(){
        $(this).find('.inplaceeditor-delete').removeClass('none');
    })
    .on('mouseout','.ud-inplaceeditor',function(){
        $(this).find('.inplaceeditor-delete').addClass('none');
    })
    .on('click','.inplaceeditor-delete',function(e){
        var url = $('.notes-list-box').attr('delete_url');
        var me = this;
        $.post(url,{note_id:$(this).parents('li').attr('note_id'),courseid:$('body').attr('courseid')},function(result){
            if(result.status){
                $(me).parents('li').remove();
            }else if(window.console && console.warn){
                console.warn('Can not delete note!');
            }
        });
        e.stopPropagation();
    });

});