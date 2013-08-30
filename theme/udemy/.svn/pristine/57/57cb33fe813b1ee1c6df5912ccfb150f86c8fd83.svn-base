//path /theme/udemy/javascript/must/block_calendar_upcoming
$(function(){
    var eventtext=$(".block_calendar_upcoming.block .content .event");
    var date=$(".block_calendar_upcoming.block .content .date").html();
    $(".block_calendar_upcoming.block .content .date").html('<time>'+date+'</time>');
    var html = '<div id="events" class="ud-courseannouncement" data-course-id="6446">'+
    '<div class="announcements-list">'+
    '<ul>';


    if(eventtext.length>0){
        $(".block_calendar_upcoming.block .content .event").each(function(i,n){
            $(n).find('br').remove();


            html += '<li class="announcement-item read" >'+$(n).html()+'</li>';
        }); 

    }else{
        $(".block_calendar_upcoming.block .content .post").each(function(j,m){
            html += ' <li class="no-announcements zero-case">'+$(m).html()+'</li>';

        });

    }

    $(".block_calendar_upcoming.block .content .footer").each(function(k,l){

        html+='<li class="load-wrapper" >'+$(l).html()+'</li>'; 

    });

    html += '</ul>  '+
    '</div>'+
    '<div class="ajax-loader-stick" style="position: relative; margin: 10px 10px 10px 0px; overflow: hidden; display: none;"></div>'+
    '</div>';

    $('.block_calendar_upcoming.block .content').html(html).show();



});
 
 