<script>
$(function(){
    /**
    $("#quan").click(function(){
            $("input[name='checks[]']").attr("checked",true);
        });
    
    $("#fan").click(function(){
        $("input[name='checks[]']").each(function(){
                if($(this).attr("checked")){
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true);
                    }
            });
        **/
        
/** 添加用户时作验证**/
        
$("button[name='bt']").toggle(function(){
    $("#divs").show();
},function(){
    $("#divs").hide();
});
        
$("input[name='sub']").click(function(){
    var firstname = $("input[name='firstname']");
    var lastname = $("input[name='lastname']");
    var email = $("input[name='email']");
    
    if($.trim(firstname.val()).length<1){
        $("#errorLog").html("<font color=red>姓氏不能为空！</font>");
        firstname.focus();
        return false ;
    }
    
    if($.trim(lastname.val()).length<1){
        $("#errorLog").html("<font color=red>名字不能为空！</font>");
        lastname.focus();
        return false ;
    }
    
    if($.trim(email.val())=="" ){
        $("#errorLog").html("<font color=red>email不能为空</font>");
        email.focus();
        return false ;
    }
    
    if(!$.trim(email.val()).match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){
        $("#errorLog").html("<font color=red>email格式有误！</font>");
        email.focus();
        return false ;
    }
});
    
});
</script>
<div style="position:relative;margin-left:100px;">
<button name="bt" style="width:90;">{$users}</button>
</div>
<div id="divs" style="position:relative;margin-left:100px;display:none;">
<div id="errorLog"></div>
    <form name="form1" action="add.php" method="POST">
    <table id="tb1">
        <tr><th>姓</th><th>名</th><th>邮箱</th></tr>
        <tr>
            <td>
                <input type="text" name="firstname" size="5"/>
            </td>
            <td>
                <input type="text" name="lastname" size="5"/>
            </td>
            <td>
                <input type="text" name="email" size="25"/>
            </td>
            <td>
                <input type="submit" value="提 交" name="sub" style="width:80px;text-align:center;"/>
            </td>
        </tr>   
       
    </table>
    
    </form>
    
</div>

<div style="position:relative;margin-left:100px;">
    <table >
        <caption align="top"><h4>参与人员</h4></caption>
        <tr><th>姓</th><th>名</th><th>邮箱</th><th>状态</th><th colspan="3">操作</th></tr>
        <tr>
            <td>
                <input type="text" name="firstname" size="5" readonly="readonly"/>
            </td>
            <td>
                <input type="text" name="lastname" size="5" readonly="readonly"/>
            </td>
            <td>
                <input type="text" name="email" size="25" readonly="readonly"/>
            </td>
            <td>
                未完成
            </td>
            <td>
                <a href="">删除</a>
            </td>
            <td>
                <a href="">下载报告</a>
            </td>
            <td>
                <a href="">生成报告</a>
            </td>
        </tr>   
        
    </table>
</div>
 