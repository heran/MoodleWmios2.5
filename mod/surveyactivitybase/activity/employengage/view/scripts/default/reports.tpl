<div class="diaocha">
<p class="top_btn"><a href="{$list_url}?cmid={$data->cmid}">{$str->relists}</a></p>
<h3>{$data->name}</h3>
<div class="dc_brief"><p>{$data->description}</p><p><span><b>{$str->startime}</b>：<i>{date('Y年m月d日',$data->starttime)}</i></span><span><b>{$str->endtime}</b>：<i>{date('Y年m月d日',$data->endtime)}</i></span><span><b>{$str->status}</b>：<i>{if $data->status eq 0}新建 {elseif $data->status eq 1}进行中{elseif $data->status eq 2}停止{elseif $data->status eq 3}已完成{/if}</i></span></p></div>
{if {$iscomplete} eq true}
<div class="search_nr">
  <div class="search_wd">
      <dl>
      <dt>{$str->weidu}：</dt>
      <dd>
          {foreach $dim1s as $k => $v}
          {if {$dim1} eq $k}
          <a href ="#" class="weidu_cur">{$v}</a>
          {else}
          <a href ="{$url}?cmid={$data->cmid}&id={$data->id}&dim1={$k}" >{$v}</a>
          {/if}
          {/foreach}
      </dd>
      <div style=" clear:both"></div>
      </dl>
  </div>
  <!--报告列表-->
  <div class="diaocha_qb diaocha1" >
  {if {$dim1} eq 'gender'}
    <div class="dc_nr_bt">
           
                  {foreach $dim2s as $k=> $v}
                      {if $dim2 eq $v}
                        <a href="#" class="bt_cur">{$v}</a>
                      {else}
                        <a href="{$url}?cmid={$data->cmid}&id={$data->id}&dim1={$dim1}&dim2={$v}" >{$v}</a>
                      {/if}
                  {/foreach}
            <div style="clear:both"></div>
          </div>
  {elseif {$dim1} eq 'provinces'}
    <div class="dc_nr_bt">
       <ul>
          {foreach $dim2s as $k => $v}
            
                <li>
                    {if $dim2[0] eq $k}
                    
                       <a  class="bt_cur" >{$k}</a>
                       <ul>
                            
                            {foreach $v as $ks => $vs}    
                                    {if $dim2[1] eq $ks}
                                    <li><a class="bt_cur" href="#" class="shiji">{$ks}</a></li>
                                    {else}
                                    <li><a  href="{$url}?cmid={$data->cmid}&id={$data->id}&dim1={$dim1}&dim2={$k}-{$ks}" class="shiji">{$ks}</a></li>
                                    {/if} 
                            {/foreach}
                       </ul>
                      
                       {else}
                         <a  >{$k}</a> 
                         <ul>
                            {foreach $v as $ks => $vs}    
                                <li><a href="{$url}?cmid={$data->cmid}&id={$data->id}&dim1={$dim1}&dim2={$k}-{$ks}" class="shiji">{$ks}</a></li>
                            {/foreach}
                       </ul>   
                    {/if}
                </li>
          {/foreach}   

       </ul>
       <div style="clear:both"></div>
    </div>     
  {elseif {$dim1} eq 'departments'}
    <div class="dc_nr_bt">
                    <form method="get" id="form1" action="{$url}?cmid={$data->cmid}&id={$data->id}&dim1={$dim1}">
                    {assign tmp $dim2s}
                    {assign v ''}
                    {assign tmp_v ''}
                    {assign tmp2 ''}
                    {foreach $dim2 as $department}
                     <select >
                            <option  value="{$v}">全部</option>
                        {foreach $tmp as $k=>$child}
                            <option  value="{if not empty($v)}{$v}-{/if}{$k}" {if $k eq $department}selected="selected"{/if}>{$k}</option>
                            {if $k eq $department}
                                {assign tmp2 $child}
                                {if empty($v)}
                                {assign tmp_v $k}
                                {else}                                    
                                {assign tmp_v $v|cat:'-'|cat:$k}
                                {/if}
                            {/if}
                        {/foreach}
                     </select>
                     {assign tmp $tmp2}
                     {assign v $tmp_v}
                    {/foreach}
                    <select >
                            <option value="{$v}">全部</option>
                        {foreach $tmp as $k=>$child}
                            <option  value="{$v}-{$k}">{$k}</option>
                        {/foreach}
                     </select>

                    </form>
                    <div style="clear:both"></div>
    </div>  
    {else}  
  {/if}
    <div class="dc_nr_tb">
      
        <a href="{$downloadpdf}" class="xiazai">{$str->downloads}</a>
        <p class="tupian_bt">{$str->tu}</p>
        <p><img src="{$radarsUrl}" width="400" height="400" /></p>
        <p class="tupian_bt">{$str->tu}</p>
        <p><img src="{$barsUrl}" width="700" height="230" /></p>
    </div>
  </div>
  

</div>
{else}
{/if}
</div>