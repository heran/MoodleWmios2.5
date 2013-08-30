//path /theme/udemy/javascript/must/block_recent_activity

$(function(){


    var text= $(".block_recent_activity.block .activity");

    var html='<div id="announcements" class="ud-courseannouncement" data-course-id="6446">'+
    '<div class="announcements-list">'+
    '<ul>';

    if(text.length>0){
        $(".block_recent_activity.block .activity").each(function(i,n){

            $(n).find('h3').remove();

            var hreft=$(n).find('a').attr('href');
            var cmid=hreft.substring(hreft.length-1);
            var atext=$(n).find('a').text();

            $(n).find('a').remove();
            $(n).find('br').remove();

            html += '<li class="announcement-item read" ><article><a href="'+hreft+'"  cmid="'+cmid+'" target="_blank">'+$(n).html()+"&nbsp;:&nbsp;"+atext+'</a></article></li>';


        } );

    }else{
        $(".block_recent_activity.block .activityhead:eq(0)").remove();
        var p=  $(".block_recent_activity.block .message").html();
        html += ' <li class="no-announcements zero-case">'+p+'</li>';


    } 


    var viewLink = $(".block_recent_activity.block .activityhead a")
    html+='<li class="load-wrapper" ><span class="more">'+$('<p></p>').append(viewLink).html()+'</span></li>';

    html += '</ul>  '+
    '</div>'+
    '<div class="ajax-loader-stick" style="position: relative; margin: 10px 10px 10px 0px; overflow: hidden; display: none;"></div>'+
    '</div>';   

    $('.block_recent_activity.block .content').html(html).show(); 

    $('.block_recent_activity.block .announcement-item a').addClass('display-by-cmid');

    $('.block_recent_activity.block .announcement-item a').attr('target','_blank');
});

