/**
* We need get the url's get params
*
*/
$.extend({
    getUrlVars: function(url){
        var vars = [], hash;
        if(!url){
            url = window.location.href;
        }
        var hashes = url.slice(url.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name,url){
        return url ? $.getUrlVars(url)[name] : $.getUrlVars()[name];
    }
});
$.extend({
    replaceBrowserUrl: function(title,url,data){
        if(!data){
            data = {};
        }
        data.title = title;
        data.url = url;
        if(window.history.pushState){
            window.history.pushState(data, title, url);
        }
    },
    replaceBrowserUrlWithParams: function(title,url,params,data){
        var need_mark = false;
        if(url.indexOf('?') < 0){
            need_mark = true;
        }
        if(params!=null)
        {
            $.each(params,function(i,n){
                i += '';
                n += '';
                url += (need_mark ? '?' : '&')+i+'='+n;
                if(need_mark){
                    need_mark = false;
                }
            });
        }
        $.replaceBrowserUrl(title,url,data ? data : null);
    }
});