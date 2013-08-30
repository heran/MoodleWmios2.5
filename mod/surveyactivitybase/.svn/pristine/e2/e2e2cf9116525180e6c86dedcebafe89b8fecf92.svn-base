<div class="diaocha">
    <p class="top_btn"><a href="{$report_url}?cmid={$data->cmid}&id={$data->id}">{$str->checkreports}</a></p>
    <h3>{$data->name}</h3>
    <div class="dc_brief"><p>{$data->description}</p><p><span><b>{$str->startime}</b>：<i>{date('Y年m月d日',$data->starttime)}</i></span><span><b>{$str->endtime}</b>：<i>{date('Y年m月d日',$data->endtime)}</i></span><span><b>{$str->status}</b>：<i>{if $data->status eq 0}新建 {elseif $data->status eq 1}进行中{elseif $data->status eq 2}停止{elseif $data->status eq 3}已完成{/if}</i></span></p></div>
    <div class="dc_list">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr class="list_bt">
    <td width="5%">{$str->name}</td>
    <td width="5%">{$str->gender}</td>
    <td width="10%">{$str->email}</td>
    <td width="26%">{$str->departments}</td>
    <td width="10%">{$str->position}</td>
    <td width="5%">{$str->rstatus}</td>
  </tr>
  {foreach $rows as $datas}
      <tr>
        <td>{$datas['firstname']}{$datas['lastname']}</td>
        <td>{$datas['gender']}</td>
        <td>{$datas['email']}</td>
        <td>
                {$datas['dplevel_one']}-
               
                {$datas['dplevel_two']}-
                
                {$datas['dplevel_three']}-
               
                {$datas['dplevel_four']}-
               
                {$datas['dplevel_five']}-
               
                {$datas['dplevel_six']}-
               
                {$datas['dplevel_seven']}-
               
                {$datas['dplevel_eight']}-
               
                {$datas['dplevel_nine']}-
                
                {$datas['dplevel_ten']}
        </td>
        <td>{$datas['position']}</td>
        <td>{if $datas['status'] eq 0}未完成{elseif $datas['status'] eq 1}已完成{/if}</td>
       
      </tr>
 {/foreach}
</table>


    </div>
    <!--分页
    <div class="page_btn">
        <a href="">上一页</a>
        {section name=loop loop=$totalpage} 
             <a class="page_a" href="?cmid=34&id=26&page={$smarty.section.loop.index+1}">{$smarty.section.loop.index+1}</a> 
        {/section} 
        <a href="#" class="page_btn_cur">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a>
        <a href="#">下一页</a>
    </div>-->
</div>