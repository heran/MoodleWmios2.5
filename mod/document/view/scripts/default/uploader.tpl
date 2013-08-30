{include 'base_header.tpl'}
<div class="document-upload-wrapper{if $has_drafts} left{/if}">
    <div class="document-content-wrapper">
        <h2 class="title">{get_string('upload_document','document')}</h2>
        <div class="upload-add-main">
            <div class="upload-plugin-wrapper">
                <div class="upload-plugin-flash">
                    <div id="upload-holder"></div>
                </div>
                <div class="upload-plugin-tip"></div>
            </div>
            <script type="text/javascript">
                    (function(){
                        window.inUpload=false;
                        //TODO 检验文件扩展名
                        window.selectFileHandler = function(fileName)
                        {
                            if(window.inUpload)
                            {

                            }else{
                                window.inUpload = true;
                                $('.upload-submit input[type="submit"]').attr("disabled","disabled");
                                document.getElementById('c_upload_single').uploadFile();
                                $('.upload-plugin-tip').html('文件上传中');
                            }
                        }

                        window.getUploadUrl = function()
                        {
                            var str = $.trim('{$url_upload}&action=file&');
                            return str;
                        };

                        window.getMaxSize = function()
                        {
                            return 20*1024*1024;
                        }

                        window.uploadSuccess = function(data)
                        {
                            var res = {};
                            try{
                                res = $.parseJSON(data);
                            }catch(err)
                            {
                                res.status = 0;
                                res.error = '';
                            }finally
                            {

                            }
                            window.inUpload = false;
                            if(res.status){
                                window.location.href='{$url_upload}&is_uploading=1&action=edit&key='+res.key;
                                return;
                            }else{
                                 $('.upload-plugin-tip').html('文件上传失败:'+res.error);
                                return false;
                            }
                        };

                        window.uploadError = function(data) {
                            alert(data);
                            var res = $.parseJSON(data);
                        };
                        var swfVersionStr = "10.2.0";
                        var xiSwfUrlStr =  "{$url_document}/js/playerProductInstall.swf";
                        var flashvars = {};
                        var params = {};
                        params.quality = "high";
                        params.bgcolor = "#ffffff";
                        params.allowscriptaccess = "always";
                        params.allowfullscreen = "true";
                        params.swLiveConnect="true";
                        var attributes = {};
                        attributes.id = "c_upload_single";
                        attributes.name = "c_upload_single";
                        attributes.align = "left";
                        attributes.verticalAlign = "top";
                        swfobject.embedSWF( "{$url_document}/js/c_upload_single.swf", "upload-holder","450", "25", swfVersionStr, xiSwfUrlStr,flashvars, params, attributes);
                    })();
                </script>
            <div class="upload-add-step1">
                <h3 class="header" id="Remind_S">文档上传须知</h3>
                <div class="taskRemind">
                    <p></p>
                    <p>文档不超过20M</p>
                    <p>为了保证文档能正常显示，我们支持以下格式的文档上传</p>
                    <table width="600" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td class="r">MS Office文档</td>
                                <td valign="top"><span class="doc-icon doc"></span>
                                    doc,docx&nbsp;&nbsp;<span class="doc-icon ppt"></span> ppt,pptx
                                    &nbsp;&nbsp;<span class="icon xls"></span> xls,xlsx &nbsp;&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="r">PDF</td>
                                <td><span class="doc-icon pdf"></span> pdf</td>
                            </tr>
                            <tr>
                                <td class="r">纯文本</td>
                                <td><span class="doc-icon txt"></span> txt</td>
                            </tr>
                            <tr>
                                <td class="r">图片文件</td>
                                <td><span class="doc-icon"></span> jpg , png</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
{include 'draft.tpl'}
{include 'base_footer.tpl'}