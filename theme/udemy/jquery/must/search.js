$(document).ready(function(){


        $("#searchbox input[type=text]")
        .focus(function(){
                $(this).parent().addClass("on");})
        .blur(function(){
                $(this).parent().removeClass("on");
        });
    }
);