var FlexPaperViewer = function () {

    var config = arguments[2].config;

    var _SWFFile,_PDFFile,_IMGFiles,_JSONFile = "",_jsDirectory="",_cssDirectory="";

    _WMode = (config.WMode!=null?config.WMode:"window");
    var _uDoc = ((config.DOC !=null)?unescape(config.DOC):null);
    var instance = "FlexPaperViewer_Instance"+((arguments[1]==="undefined")?"":arguments[1]);
    if (_uDoc != null) {
        _SWFFile = translateUrlByFormat(_uDoc,"swf");
        _PDFFile = translateUrlByFormat(_uDoc,"pdf");
        _JSONFile = translateUrlByFormat(_uDoc,"json");
        _IMGFiles = translateUrlByFormat(_uDoc,"png");
    }
    _SWFFile = (config.SwfFile!=null?config.SwfFile:_SWFFile);
    _SWFFile = (config.SWFFile!=null?config.SWFFile:_SWFFile);
    _PDFFile = (config.PDFFile!=null?config.PDFFile:_PDFFile);
    _IMGFiles = (config.IMGFiles!=null?config.IMGFiles:_IMGFiles);
    _IMGFiles = (config.PageImagePattern!=null?config.PageImagePattern:_IMGFiles);
    _JSONFile = (config.JSONFile!=null?config.JSONFile:_JSONFile);
    _jsDirectory = (config.jsDirectory!=null?config.jsDirectory:"js/");
    _cssDirectory = (config.cssDirectory!=null?config.cssDirectory:"css/");
    if(_SWFFile.indexOf("{")==0){
        _SWFFile = escape(_SWFFile);
    }
    window[instance] = flashembed(arguments[1], {
        src: arguments[0]+".swf", version: [10, 0], expressInstall: "js/expressinstall.swf", wmode: _WMode }
    ,{
        SwfFile : _SWFFile,
        PdfFile : _PDFFile, 
        IMGFiles : _IMGFiles, 
        JSONFile : _JSONFile, 
        useCustomJSONFormat : config.useCustomJSONFormat, 
        JSONPageDataFormat : config.JSONPageDataFormat, 
        Scale : config.Scale, 
        ZoomTransition : config.ZoomTransition, 
        ZoomTime : config.ZoomTime, 
        ZoomInterval : config.ZoomInterval, 
        FitPageOnLoad : config.FitPageOnLoad, 
        FitWidthOnLoad : config.FitWidthOnLoad, 
        FullScreenAsMaxWindow : config.FullScreenAsMaxWindow, 
        ProgressiveLoading : config.ProgressiveLoading, 
        MinZoomSize : config.MinZoomSize, 
        MaxZoomSize : config.MaxZoomSize, 
        SearchMatchAll : config.SearchMatchAll, 
        SearchServiceUrl : config.SearchServiceUrl, 
        InitViewMode : config.InitViewMode,
        BitmapBasedRendering : config.BitmapBasedRendering, 
        StartAtPage : config.StartAtPage, 
        ViewModeToolsVisible : ((config.ViewModeToolsVisible!=null)?config.ViewModeToolsVisible:true),
        ZoomToolsVisible : ((config.ZoomToolsVisible!=null)?config.ZoomToolsVisible:true), 
        NavToolsVisible : ((config.NavToolsVisible!=null)?config.NavToolsVisible:true), 
        CursorToolsVisible : ((config.SearchToolsVisible!=null)?config.CursorToolsVisible:true),
        SearchToolsVisible : ((config.SearchToolsVisible!=null)?config.SearchToolsVisible:true), 
        RenderingOrder : config.RenderingOrder, 
        localeChain : config.localeChain, 
        jsDirectory : _jsDirectory, 
        cssDirectory : _cssDirectory, 
        key : config.key }
    );
}



var FlexPaperViewer_HTML=function (e){
    var d1='flexpaper_handlers.js"><\/script>',e1='flexpaper.js"><\/script>',F1='<script type="text/javascript" src="';
    this.config=e;
    this.document=this.config.document;
    this.da=this.config.rootid;
    this.ka="#"+this.da;
    this.qc="viewercontainer_"+this.da;
    this.Fa="#"+this.qc;
    this.ie=this.qc+"_textoverlay";
    this.rd="#"+this.ie;
    this.Ab="flexpaper_selected_default";
    this.Ce="#000000";
    this.ja=1;
    this.ic=this.Dc=q;
    this.renderer=this.config.renderer;
    this.ba="Portrait";
    this.Na=w;
    this.ia=e.toolbarid==q?"toolbar_"+this.da:e.toolbarid;
    this.Fb=e.toolbarid!=q;
    this.na="#"+this.ia;
    this.bb=w;
    this.kf="highlight";
    this.va=R();
    this.scale=this.config.document.Scale;
    this.loadDoc=function (d,c,e){
        this.Na=w;
        this.document.numPages=c;
        this.document.dimensions=e;
        this.renderer=d;
        this.show();
        this.config.document.FitPageOnLoad||this.va?this.fitheight():this.config.document.FitWidthOnLoad?this.fitwidth():this.toolbar&&this.toolbar.Ra&&this.toolbar.Ra.setValue(this.toolbar.Ra.Ie(),p);
    }
    ;
    this.getDimensions=function (){
        return _this.renderer.getDimensions();
    }
    ;
    this.Sc=function (d){
        var c=!this.va||typeof d.originalEvent.touches===b1?d.pageX:d.originalEvent.touches[0].pageX,e=!this.va||typeof d.originalEvent.touches===b1?d.pageY:d.originalEvent.touches[0].pageY;
        if (this.bb||this.va){
            if (d.target&&d.target.id&&d.target.id.indexOf("page")>=0&&d.target.id.indexOf("word")>=0&&(hoverPage=parseInt(d.target.id.substring(d.target.id.indexOf("_")+1)),hoverPageObject=V(this.da)),hoverPageObject&&window.jc)window.b=hoverPageObject.match({
                left:c,top:e}
            ,w),this.Eb(p),this.Va=hoverPageObject.qb(window.a,window.b,p,this.Ab);
        }
        else d.target&&d.target.id&&d.target.id.indexOf("page")>=0&&(hoverPage=parseInt(d.target.id.substring(d.target.id.indexOf("_")+1)),hoverPageObject=V(this.da));
    }
    ;
    this.qb=A();
    this.Eb=function (d){
        jQuery(".flexpaper_pageword_"+this.da).removeClass("flexpaper_selected");
        jQuery(".flexpaper_pageword_"+this.da).removeClass("flexpaper_selected_default");
        d&&jQuery(".flexpaper_pageword_"+this.da).each(function (){
            var r1="flexpaper_selected_blue",o9="flexpaper_selected_green",w9="flexpaper_selected_orange",v_="isMark",o1="flexpaper_selected_yellow";
            jQuery(this).hasClass(o1)&&!jQuery(this).data(v_)&&jQuery(this).removeClass(o1);
            jQuery(this).hasClass(w9)&&!jQuery(this).data(v_)&&jQuery(this).removeClass(w9);
            jQuery(this).hasClass(o9)&&!jQuery(this).data(v_)&&jQuery(this).removeClass(o9);
            jQuery(this).hasClass(r1)&&!jQuery(this).data(v_)&&jQuery(this).removeClass(r1);
            jQuery(this).hasClass("flexpaper_selected_strikeout")&&!jQuery(this).data(v_)&&jQuery(this).removeClass("flexpaper_selected_strikeout");
        }
        );
    }
    ;
    this.Uc=function (d){
        if (this.bb||this.va){
            if (hoverPageObject){
                if (this.va){
                    var c=q;
                    typeof d.originalEvent.touches!=b1&&(c=d.originalEvent.touches[0]||d.originalEvent.changedTouches[0]);
                    if (c!=q&&this.tb==c.pageX&&this.ub==c.pageY){
                        this.Eb();
                        this.Va=hoverPageObject.qb(window.a,window.b,w,this.Ab);
                        var e=this.Va.$c;
                    }
                    if (c!=q)this.tb=c.pageX,this.ub=c.pageY;
                }
                window.b=hoverPageObject.match({
                    left:d.pageX,top:d.pageY}
                ,w);
                this.Va!=q&&jQuery(this.ka).trigger("onSelectionCreated","text");
                window.jc=w;
                window.a=q;
                window.b=q;
            }
        }
        else if (hoverPageObject)window.b=hoverPageObject.match({left:d.pageX,top:d.pageY},w),window.jc=w,this.Eb(),this.Va=hoverPageObject.qb(window.a,window.b,w,this.Ab),e=this.Va.$c,e==1&&(window.a.bc&&this.gotoPage(window.a.bc),window.a.rc&&window.open(window.a.rc,"_blank",""));
    }
    ;
    this.Rc=function (d){
        var c=!this.va||typeof d.originalEvent.touches===b1?d.pageX:d.originalEvent.touches[0].pageX,e=!this.va||typeof d.originalEvent.touches===b1?d.pageY:d.originalEvent.touches[0].pageY;
        this.tb=c;
        this.ub=e;
        this.Va=q;
        if (this.bb||this.va){
            if (!hoverPageObject)if (this.va){
                if (d.target&&d.target.id&&d.target.id.indexOf("page")>=0&&d.target.id.indexOf("word")>=0&&(hoverPage=parseInt(d.target.id.substring(d.target.id.indexOf("_")+1)),hoverPageObject=V(this.da)),!hoverPageObject){
                    window.a=q;
                    return ;
                }
            }
            else {
                window.a=q;
                return ;
            }
            window.a=hoverPageObject.match({
                left:c,top:e}
            ,p);
            if (window.a)return window.jc=p,this.Eb(),this.Va=hoverPageObject.qb(window.a,window.b,w,this.Ab),w;
            else {
                if (!this.va)this.Eb(),this.Va=hoverPageObject.qb(window.a,window.b,w,this.Ab);
                window.jc=w;
                return p;
            }
        }
        else window.a=hoverPageObject?hoverPageObject.match({
            left:c,top:e}
        ,p):q;
    }
    ;
    this.bindEvents=function (){
        var d=this;
        hoverPage=0;
        hoverPageObject=q;
        jQuery(document).ready(function (){
            jQuery(d.ka).bind("contextmenu",J(w));
            window.annotations&&(jQuery(d.ka).on("touchstart",function (c){
                return c.originalEvent.touches.length>1?l:d.Rc(c);
            }
            ),jQuery(d.ka).on("touchmove",function (c){
                return c.originalEvent.touches.length>1?l:d.Sc(c);
            }
            ),jQuery(d.ka).on("touchend",function (c){
                return c.originalEvent.touches.length>1?l:d.Uc(c);
            }
            ));
            jQuery(document).bind("mousemove",function (c){
                return d.Sc(c);
            }
            );
            jQuery(document).bind("mousedown",function (c){
                return d.Rc(c);
            }
            );
            jQuery(document).bind("mouseup",function (c){
                return d.Uc(c);
            }
            );
            document.Ue=J(w);
            document.attachEvent&&document.attachEvent("ondragstart",J(w));
        }
        );
    }
    ;
    this.initialize=function (){
        K();
        this.ba=window.zine||isIOSDevice()?"TwoPage":this.config.document.InitViewMode;
        this.toolbar=new ca(this,this.document);
        jQuery("#"+this.qc).length==0&&jQuery(this.ka).wrap("<div id='"+this.qc+"' style='position:relative;left:0px;top:0px;width:100%;height:100%;background-color:#d4dcdc;'>");
        jQuery(this.na).length==0&&jQuery(this.Fa).prepend("<div id='"+this.ia+"' class='flexpaper_toolbarstd' style='z-index:200;overflow-y:hidden;overflow-x:hidden;'></div>");
        this.va?isIOSDevice()&&!isOldIOSDevice()?jQuery(this.ka).height(jQuery(this.ka).height()-35):jQuery(this.ka).height(jQuery(this.ka).height()-25):jQuery(this.ka).height(jQuery(this.ka).height()-13);
        hoverPage=0;
        hoverPageObject=q;
        if (window.zine)this.toolbar.Xa=new FlexPaperViewerZine_Toolbar(this.toolbar),this.toolbar.Xa.create(this.ia);
        else if (this.Fb?jQuery(this.na).prependTo(this.Fa):this.toolbar.create(this.ia),window.annotations)jQuery(this.Fa).append("<div id='"+this.ia+"_annotations_container' style='height:50px;'><div id='"+this.ia+"_annotations_popup' class='flexpaper_toolbarstd' style='z-index:200;position:relative;visibility:hidden;border-width:0px;width:100px;z-index:200;overflow-y:hidden;margin-left:1px;overflow-x:hidden;margin-top:-14px'></div><div id='"+this.ia+"_annotations' class='flexpaper_toolbarstd' style='z-index:200;overflow-y:hidden;overflow-x:hidden;'></div></div>"),this.cc=new FlexPaperViewerAnnotations_Plugin(this,this.document),this.cc.create(this.ia+"_annotations"),this.cc.bindEvents(jQuery(this.ka));
    }
    ;
    this.show=function (){
        var d=this;
        jQuery("#"+d.toolbar.vc).hide();
        var c=Math.pow(9,3),e=Math.pow(6,2),h=d.config.key!=q&&d.config.key.length>0&&d.config.key.indexOf("@")>=0,g="",g="",g=["d0ma1n"],g=g[0],e=parseInt(e)+W(p)+"AdaptiveUId0ma1n",c=Z(parseInt(c)+(h?d.config.key.split("$")[0]:W(p))+g),e=Z(e),c="$"+c.substring(11,30).toLowerCase(),e="$"+e.substring(11,30).toLowerCase();
        !X()&&!(d.config.key==c||d.config.key==e||h&&c=="$"+d.config.key.split("$")[1])?alert("License key not accepted. Please check your configuration settings."):setTimeout(function (){
            d.nd();
            d.Fb||d.toolbar.bindEvents(jQuery(d.ka));
            d.toolbar.Xa!=q&&d.toolbar.Xa.bindEvents(jQuery(d.ka));
            d.Kc();
        }
        ,10);
    }
    ;
    this.md=function (){
        R()?(this.Na=p,this.config.document.FitWidthOnLoad&&this.ba!="TwoPage"&&this.fitwidth(),(this.config.document.FitPageOnLoad||this.ba=="TwoPage")&&this.fitheight(),this.aa.cb(),this.aa.vb()):(this.Na=p,this.Fb||this.toolbar.xd(this.config.document.MinZoomSize,this.config.document.MaxZoomSize),this.config.document.FitPageOnLoad||this.ba=="TwoPage"?this.fitheight():this.config.document.FitWidthOnLoad&&this.ba!="TwoPage"?this.fitwidth():this.Zoom(this.config.document.Scale),window.onCurrentPageChanged&&onCurrentPageChanged(this.aa.ga+1));
    }
    ;
    this.hc=A();
    this.Rd=function (){
        var d=this;
        if (d.Dc&&d.Dc.Le())d.aa.yb(),d.aa.cb(),d.aa.vb();
        else {
            if (d.ic!=q)window.clearTimeout(d.ic),d.ic=q;
            d.ic=setTimeout(function (){
                d.aa.yb();
                d.aa.cb();
                d.aa.vb();
                d.Rd();
            }
            ,50);
        }
    }
    ;
    this.nd=function (){
        this.ja=1;
        jQuery(this.ka).empty();
        this.renderer.sb=w;
        jQuery(this.rd).remove();
    }
    ;
    this.Kc=function (d){
        this.aa=new $(this.da,this.document,this.renderer,this,d);
        this.aa.create(jQuery(this.ka));
    }
    ;
    this.previous=function (){
        this.aa.previous();
    }
    ;
    this.setCurrentCursor=function (d){
        if (d=="ArrowCursor")this.bb=w,addCSSRule(".flexpaper_pageword","cursor","default");
        if (d=="TextSelectorCursor")this.bb=p,addCSSRule(".flexpaper_pageword","cursor","text");
        jQuery(this.na).trigger("onCursorChanged",d);
        if (window.onCursorModeChanged)window.onCursorModeChanged(d);
    }
    ;
    this.printPaper=function (){
        var u_="<script type='text/javascript' src='",E1="#printFrame_",d=q;
        this.renderer.Ya()=="ImagePageRenderer"&&(d="{jsonfile : '"+this.renderer.config.jsonfile+"',compressedJsonFormat : "+this.renderer.config.ze+",pageImagePattern : '"+this.renderer.config.pageImagePattern+"'}");
        this.renderer.Ya()=="CanvasPageRenderer"&&(d="'"+this.renderer.file+"'");
        if (jQuery(E1+this.da).length>0){
            var c=jQuery(E1+this.da)[0].contentWindow.document;
            c.open();
            c.write("<html>");
            c.write("<head>");
            c.write(u_+this.config.jsDirectory+"jquery.min.js'><\/script>");
            c.write(F1+this.config.jsDirectory+e1);
            c.write(F1+this.config.jsDirectory+d1);
            c.write(u_+this.config.jsDirectory+"FlexPaperViewer.js'><\/script>");
            c.write("<style type='text/css' media='print'>html, body { height:100%; }body { margin:0; padding:0; } .ppage { display:block;max-width:210mm;max-height:297mm;margin-bottom:20px;margin-top:0px; } .ppage_break { page-break-after : right; } .ppage_none { page-break-after : avoid; } @page {}</style>");
            c.write("</head>");
            c.write("<body onload=\"printDocument('"+this.renderer.Ya()+"',"+d+');"></body></html>');
            c.close();
        }
    }
    ;
    this.switchMode=function (d,c){
        var e=this;
        if (d=="Tile")e.ba="ThumbView";
        if (d=="Portrait")e.ba="Portrait";
        if (d=="TwoPage")e.ba="TwoPage";
        e.nd();
        e.aa.Sd();
        e.renderer.ma=-1;
        if (d!="TwoPage")c!=q?e.aa.ga=c-1:c=1;
        e.Kc(c);
        jQuery(e.na).trigger("onViewModeChanged",e);
        if (window.onViewModeChanged)window.onViewModeChanged(e.ba);
        setTimeout(function (){
            R()?d!="TwoPage"&&e.fitwidth():e.fitheight();
            d!="TwoPage"&&e.Vb(c);
        }
        ,100);
    }
    ;
    this.fitwidth=function (){
        var d=jQuery(this.aa.ea).width()-15,c=this.aa.aa[this.ja-1].dimensions.width/this.aa.aa[this.ja-1].dimensions.height;
        R()?(d=d/(this.aa.aa[this.ja-1].sa*c)-0.03,window.FitWidthScale=d,this.ab(d)):(d=d/(this.aa.aa[this.ja-1].sa*this.document.MaxZoomSize*c)-0.012,window.FitWidthScale=d,d*this.document.MaxZoomSize>=this.document.MinZoomSize&&d<=this.document.MaxZoomSize&&this.ab(this.document.MaxZoomSize*d));
    }
    ;
    this.ab=function (d){
        var U_="scrollHeight",c=this;
        if (c.Na){
            var e=jQuery(c.aa.ea).prop(U_),h=jQuery(c.aa.ea).scrollTop(),e=h>0?h/e:0;
            if (c.nb!=q)window.clearTimeout(c.nb),c.nb=q;
            jQuery(".flexpaper_pageword_"+c.da).remove();
            c.nb=setTimeout(function (){
                c.Lb();
                c.aa.ra();
            }
            ,500);
            if (d>0){
                c.aa.Ia(d);
                c.scale=d;
                c.aa.aa[0].Ub();
                jQuery(c.na).trigger("onZoomFactorChanged",Math.round(d/c.document.MaxZoomSize*100*c.document.MaxZoomSize)+"%");
                if (window.FitWidthScale!=b1&&Math.round(window.FitWidthScale*100)==Math.round(d/c.document.MaxZoomSize*100)){
                    if (jQuery(c.na).trigger("onFitModeChanged","FitWidth"),window.onFitModeChanged)window.onFitModeChanged("Fit Width");
                }
                else if (window.FitHeightScale!=b1&&Math.round(window.FitHeightScale*100)==Math.round(d/c.document.MaxZoomSize*100)){
                    if (jQuery(c.na).trigger("onFitModeChanged","FitHeight"),window.onFitModeChanged)window.onFitModeChanged("Fit Height");
                }
                else if (jQuery(c.na).trigger("onFitModeChanged","FitNone"),window.onFitModeChanged)window.onFitModeChanged("Fit None");
                c.document.aa.vb();
                c.document.aa.yb();
                d=jQuery(c.aa.ea).prop(U_);
                R()||jQuery(c.aa.ea).scrollTo({
                    top:d*e+"px"}
                ,0,{
                    axis:"y"}
                );
            }
        }
    }
    ;
    this.Lb=function (){
        if (this.nb!=q)window.clearTimeout(this.nb),this.nb=q;
        this.renderer.Ya()=="CanvasPageRenderer"&&jQuery(".flexpaper_pageword_"+this.da).remove();
        for (var d=0;
        d<this.document.numPages;
        d++)this.aa.aa[d].Da?this.aa.aa[d].renderer.Mb(this.aa.aa[d],p):this.aa.aa[d].wa=w;
    }
    ;
    this.Zoom=function (d){
        if (!(d>this.document.MaxZoomSize)){
            if (window.onScaleChanged)window.onScaleChanged(d);
            d/=this.document.MaxZoomSize;
            jQuery(this.na).trigger("onScaleChanged",d);
            d*this.document.MaxZoomSize>=this.document.MinZoomSize&&d<=this.document.MaxZoomSize&&this.ab(this.document.MaxZoomSize*d);
        }
    }
    ;
    this.sliderChange=function (d){
        d>this.document.MaxZoomSize||(d/=this.document.MaxZoomSize,d*this.document.MaxZoomSize>=this.document.MinZoomSize&&d<=this.document.MaxZoomSize&&this.ab(this.document.MaxZoomSize*d));
    }
    ;
    this.searchText=function (d){
        var c=this;
        if (d!=q)if (c.ba=="ThumbView")c.switchMode("Portrait"),setTimeout(function (){
            c.searchText(d);
        }
        ,1E3);
        else {
            var e=c.renderer.ua,h=e.length;
            if (!window.jb)window.jb=0;
            if (!window.ta)window.ta=-1;
            d!=q&&d.length>0&&(d=d.toLowerCase());
            if (window.ec!=d)window.Qa=-1,window.ec=d,window.jb=0,window.ta=-1;
            for (window.ta==-1?window.ta=parseInt(c.ja):window.Qa+=d.length;
            window.ta-1<h;
            ){
                window.Qa=e[window.ta-1].indexOf(d,window.Qa==-1?0:window.Qa);
                if (window.Qa>=0){
                    c.ja!=window.ta&&c.ja!=window.ta+1&&c.ba=="TwoPage"?c.gotoPage(window.ta,function (){
                        window.Qa-=d.length;
                        c.searchText(d);
                    }
                    ):(window.jb++,this.document.aa.aa[window.ta-1].load(function (){
                        c.document.aa.aa[window.ta-1].Ga(window.ec,w);
                    }
                    ));
                    break }
                window.ta++;
                window.Qa=-1;
                window.jb=0;
            }
            if (window.Qa==-1)window.Qa=-1,window.jb=0,window.ta=-1,alert("No more search matches."),c.gotoPage(1);
        }
    }
    ;
    this.Te=A();
    this.Ze=A();
    this.kc=function (d){
        this.aa.kc(d);
    }
    ;
    this.Zb=function (){
        var d=this;
        d.aa.Zb();
        jQuery(d.aa).on("onDrawingStopped",function (c,e,h){
            jQuery(d).trigger("onDrawingStopped",[e,h]);
        }
        );
    }
    ;
    this.Wb=function (){
        this.aa.Wb();
    }
    ;
    this.fc=function (){
        this.aa.fc();
    }
    ;
    this.fitheight=function (){
        if (this.ba=="Portrait"||this.ba=="TwoPage"){
            var d=this.aa.aa[this.ja-1].dimensions.width/this.aa.aa[this.ja-1].dimensions.height;
            if (R())c=jQuery(this.aa.ea).height()-(this.ba=="TwoPage"?40:0),c/=this.aa.aa[this.ja-1].sa,e=this.aa.aa[this.ja-1],e=e.sa*(e.dimensions.ya/e.dimensions.Pa)*c,this.ba=="TwoPage"&&e*2>jQuery(this.ka).width()&&(c=jQuery(this.ka).width()-(this.ba=="TwoPage"?0:0),c/=this.aa.aa[this.ja-1].sa*4),window.FitHeightScale=c,this.ab(c);
            else {
                var c=jQuery(this.aa.ea).height()-(this.ba=="TwoPage"?20:0);
                c/=this.aa.aa[this.ja-1].sa*this.document.MaxZoomSize;
                var e=this.aa.aa[this.ja-1],e=e.sa*(e.dimensions.ya/e.dimensions.Pa)*this.document.MaxZoomSize*c;
                this.ba=="TwoPage"&&e*2>jQuery(this.ka).width()&&(c=jQuery(this.aa.ea).width()-35-(this.ba=="TwoPage"?20:0),c=c/1.6/(this.aa.aa[this.ja-1].sa*this.document.MaxZoomSize*d));
                window.FitHeightScale=c;
                c*this.document.MaxZoomSize>=this.document.MinZoomSize&&c<=this.document.MaxZoomSize&&this.ab(this.document.MaxZoomSize*c);
            }
        }
    }
    ;
    this.next=function (){
        this.aa.next();
    }
    ;
    this.gotoPage=function (d,c){
        var e=this;
        e.ba=="ThumbView"&&(isIOSDevice()?e.switchMode("TwoPage",d):e.switchMode("Portrait",d));
        d!=e.getTotalPages()&&(e.ba=="Portrait"&&e.aa.scrollTo(d),e.ba=="TwoPage"&&setTimeout(function (){
            e.aa.Ib(d,c);
        }
        ,300));
    }
    ;
    this.getCurrPage=function (){
        return this.aa.ga+1;
    }
    ;
    this.getTotalPages=function (){
        return this.aa.getTotalPages();
    }
    ;
    this.Vb=function (d){
        this.ja=d;
        this.toolbar.de(d);
        this.aa.ga=this.ja-1;
        this.ba=="TwoPage"&&this.aa.ga==this.aa.getTotalPages()-1&&this.aa.ga%2!=0&&(this.aa.ga+=1);
        this.cc!=q&&this.cc.Be(this.ja);
    }
    ;
    this.openFullScreen=function (){
        var H1='",',d="",d="toolbar=no, location=no, scrollbars=no, width="+screen.width;
        d+=", height="+screen.height;
        d+=", top=0, left=0";
        d+=", fullscreen=yes";
        nw=window.open("","windowname4",d);
        nw.Xe=d;
        d="";
        d+='<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';
        d+="<head>";
        d+='<link rel="stylesheet" type="text/css" href="'+this.config.cssDirectory+'flexpaper.css" />';
        d+=F1+this.config.jsDirectory+'jquery.min.js"><\/script>';
        d+=F1+this.config.jsDirectory+'jquery.extensions.min.js"><\/script>';
        d+=F1+this.config.jsDirectory+e1;
        d+=F1+this.config.jsDirectory+d1;
        d+="</head>";
        d+='<body onload="openViewer();">';
        d+='<div style="position:absolute;left:10px;top:10px;width:98%;height:93%;">';
        d+=this.Fb?'<div id="toolbar1" class="flexpaper_toolbarstd">'+jQuery(this.na).html()+"</div>":"";
        d+='<div id="documentViewer" class="flexpaper_viewer"></div>';
        d+='<script type="text/javascript">';
        d+="function openViewer(){";
        d+='var fp = new FlexPaperViewer("FlexPaperViewer","documentViewer", ';
        d+=this.Fb?'"toolbar1",':"";
        d+="{ config : {";
        d+="";
        d+='SWFFile : "'+this.document.SWFFile+H1;
        d+='IMGFiles : "'+this.document.IMGFiles+H1;
        d+='JSONFile : "'+this.document.JSONFile+H1;
        d+='PDFFile : "'+this.document.PDFFile+H1;
        d+="";
        d+="Scale : "+this.scale+",";
        d+='ZoomTransition : "'+this.document.ZoomTransition+H1;
        d+="ZoomTime : "+this.document.ZoomTime+",";
        d+="ZoomInterval : "+this.document.ZoomInterval+",";
        d+="FitPageOnLoad : "+this.document.FitPageOnLoad+",";
        d+="FitWidthOnLoad : "+this.document.FitWidthOnLoad+",";
        d+="FullScreenAsMaxWindow : "+this.document.FullScreenAsMaxWindow+",";
        d+="ProgressiveLoading : "+this.document.ProgressiveLoading+",";
        d+="MinZoomSize : "+this.document.MinZoomSize+",";
        d+="MaxZoomSize : "+this.document.MaxZoomSize+",";
        d+="SearchMatchAll : "+this.document.SearchMatchAll+",";
        d+='InitViewMode : "'+this.document.InitViewMode+H1;
        d+='RenderingOrder : "'+this.document.RenderingOrder+H1;
        d+="useCustomJSONFormat : "+this.document.useCustomJSONFormat+",";
        this.document.JSONPageDataFormat!=q&&(d+="JSONPageDataFormat : {",d+='pageWidth : "'+this.document.JSONPageDataFormat.ac+H1,d+='pageHeight : "'+this.document.JSONPageDataFormat.$b+H1,d+='textCollection : "'+this.document.JSONPageDataFormat.Ob+H1,d+='textFragment : "'+this.document.JSONPageDataFormat.Pb+H1,d+='textFont : "'+this.document.JSONPageDataFormat.Ic+H1,d+='textLeft : "'+this.document.JSONPageDataFormat.nc+H1,d+='textTop : "'+this.document.JSONPageDataFormat.oc+H1,d+='textWidth : "'+this.document.JSONPageDataFormat.pc+H1,d+='textHeight : "'+this.document.JSONPageDataFormat.mc+'"',d+="},");
        d+="ViewModeToolsVisible : "+this.document.ViewModeToolsVisible+",";
        d+="ZoomToolsVisible : "+this.document.ZoomToolsVisible+",";
        d+="NavToolsVisible : "+this.document.NavToolsVisible+",";
        d+="CursorToolsVisible : "+this.document.CursorToolsVisible+",";
        d+="SearchToolsVisible : "+this.document.SearchToolsVisible+",";
        d+='jsDirectory : "'+this.config.jsDirectory+H1;
        d+='key : "'+this.config.key+H1;
        d+="";
        d+='localeChain: "'+this.document.localeChain+'"';
        d+="}});}";
        d+="<\/script>";
        d+="</div>";
        d+="</body>";
        d+="</html>";
        nw.document.write(d);
        nw.ne=p;
        window.focus&&nw.focus();
        nw.document.close();
    }
    ;
}

