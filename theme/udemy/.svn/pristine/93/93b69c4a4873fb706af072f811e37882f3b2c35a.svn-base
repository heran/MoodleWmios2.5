//path /theme/udemy/javascript/must/page_footer.js
// The "@2012 Wmios " string need be at the page's bottom
$(function(){
    var resize = function(){
        $('#page').css({minHeight:$(window).height()});
        $('#page>#wrapper').css({minHeight:$(window).height(),
                                 paddingBottom:$('#page-footer').height()});
        $('#page-footer').css({position: 'absolute',bottom:0,left:0});
    }
    resize();
    $(window).resize(resize);
});

$(function(){
    $('.uv-tray-item-icon').mouseenter(function(){
        $(this).siblings('.uv-bubble.uv-fade').removeClass('uv-is-hidden');
    }).mouseleave(function(){
        $(this).siblings('.uv-bubble.uv-fade').addClass('uv-is-hidden');
    }).click(function(){
        $(this).parent().siblings('.uv-tray-item').find('.uv-popover').addClass('uv-is-hidden');
        $(this).siblings('.uv-popover').toggleClass('uv-is-hidden');
        $(this).parent().toggleClass('uv-is-selected');
        $(this).siblings('.uv-bubble.uv-fade').toggleClass('uv-is-hidden');
    });
});