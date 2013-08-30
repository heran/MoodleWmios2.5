$(document).ready(function(){
    $(document).on('click', '.ddown > a', function(event){
        event&&event.preventDefault();
        event&&event.stopPropagation();
        var $parent=$(event.currentTarget).parent();
        if($parent.hasClass('on')){
            $parent.removeClass('on');
        }else{
            $('.ddown').removeClass("on");
            $parent.addClass('on');
        }
        return false;
    });
    $(document).on('click', 'html, .ddown.on > a', function(event){
        if(event.target.id=='adminsearchquery'){
           return;
        }
        var inUl = $(event.target).parents('ul').siblings('a');
        if(typeof inUl != 'undefined' && inUl.length >0){
            var href = $(event.target).attr('href');
            if(typeof href != 'undefined' && (href == '#' || href== '')){
                return false;
            }
        }
        $(".ddown.on").removeClass("on");
    });

    $(document).on('mouseenter','.ddown ul>li',function(){
        if($(this).children('ul').length == 0){
            return;
        }
        var y  = $(this).children('a').position().top;
        if($(this).parents('.ddown').hasClass('down-right')){
            $(this).children('ul').show().css({top:y,left:0-$(this).children('ul').width()});
        }else{            
            var x = $(this).width();
            $(this).children('ul').show().css({top:y,left:x});
        }

    });
    $(document).on('mouseleave','.ddown ul>li',function(){
        var f =1;
        $(this).children('ul').hide()

    })


});