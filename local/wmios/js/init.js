//Place Holder
$(function(){$.Placeholder.init();});

//fancybox
$(function(){$(".fancybox").fancybox({padding:0});});
$(function(){
    $(".fancybox-thumb").fancybox({
        prevEffect    : 'none',
        nextEffect    : 'none',
        helpers    : {
            title    : {
                type: 'outside'
            },
            thumbs    : {
                width    : 50,
                height    : 50
            },
            buttons    : {}
        }
    });
});