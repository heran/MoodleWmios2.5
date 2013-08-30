
$(document).on('click','.my-courses-nav .gray-nav li',function(){
    $('.my-courses-nav .gray-nav li').removeClass('current');
    $(this).addClass('current');   

    var type = $(this).attr('type');
    $('#my-courses #list ul').hide();
    $('#my-courses #list ul[type="'+type+'"]').show();
    return false; 

});

/**************************************************************/

$(function(){

    $('#my-courses #list ul').each(function(i,n){

        if($(n).find('li').length>0){
            $('.my-courses-nav .gray-nav li:eq('+i+')').click();   
            return false;
        }
    });


});

