var M = M || {};
M.W = M.W || {};
M.W.course_list = M.W.course_list || {};
var M_W_course_list = M.W.course_list;

/**
* course category node
*
* @param id
* @param name
*/
M.W.course_list.category = function(id,name){

    /**
    * category's id
    *
    * @type {int}
    */
    this.id = id;

    /**
    * category's name
    *
    * @type {string}
    */
    this.name = name;

    /**
    * category's parent
    *
    * @type {category}
    */
    this.parent = null;

    /**
    * category's children
    *
    * @type {category[]}
    */
    this.children=[];

    /**
    * add child
    *
    * @param {category} child
    * @returns {bool}
    */
    this.add_child = function(child){
        if(!this.has_child(child)){
            this.children.push(child);
            child.parent = this;
        }
    }

    /**
    * remove one child
    *
    * @param {category} child
    * @returns {category[]} old children list
    */
    this.remove_child = function(child){
        return this.children.splice(this.get_child_index(child),1);
    }

    /**
    * True if has a child
    *
    * @param {category} child
    * @returns {bool}
    */
    this.has_child = function(child){
        return this.get_child_index(child) !== false;
    }

    /**
    * where the child in the children list
    *
    * @param {category} child
    * @returns {int}
    */
    this.get_child_index = function(child){
        for (var i = 0; i < this.children.length; i++) {
            if (this.children[i].id == child.id) {
                return i;
            }
        }
        return false;
    }

    /**
    * @param {string} name
    * @returns {null|category}
    */
    this.get_child_by_name = function(name){
        for(var i in this.children){
            if(this.children[i].name == name){
                return this.children[i];
            }
        }
        return null;
    }

    /**
    * @param {category} root
    * @param {string[]} names
    * @returns {null|category} this.parent
    */
    this.match_parent = function(root, names){
        var parent = root;
        if(names.length){
            for(var i=0; i<names.length ; i++){
                parent = parent.get_child_by_name($.trim(names[i]));
                if(!parent){
                    break;
                }
            }
            if(!parent){
                parent = root;
            }
        }
        parent.add_child(this);
        return parent;
    }
}

/**
* create category root
*
* @returns {category}
*/
M.W.course_list.make_course_category = function (){
    var category = this.category;
    var rootname = M.util.get_string('all_a_category','theme_udemy',M.util.get_string('category','core'));
    var category_root = new category(0,rootname);
    var options = $('select[name=categoryid] option');
    $(options).each(function(i,n){
        var names = $($(n).text().split('/')).map(function(){
            return $.trim(this);
        });
        var name = names[names.length-1] + '';
        var id = $(n).val()-0;
        var node = new category(id, name);
        names.splice(names.length-1,1);
        var parent = node.match_parent(category_root, names);
    });
    return category_root;
}

/**
* find one category with it's parent
*
* @param {category} node
* @param {int} cat_id
*/
M.W.course_list.find_course_category = function (node, cat_id){
    if(node.id == cat_id){
        return node;
    }else if(!node.children.length){
        return null;
    }else{
        for(var i in node.children){
            var cat = M_W_course_list.find_course_category(node.children[i], cat_id);
            if(cat){
                return cat;
            }
        }
        return null;
    }
}

$(function(){

    //only parse course/index.php?browse=courses
    //course/index.php?browse=courses&search={keywords}
    //course/index.php?browse=courses&categoryid={id}
    if(window.location.href.indexOf('course/index.php') < 0 ||
        ($.getUrlVar('browse')!== 'courses' && !$.getUrlVar('search') ) ){
        return;
    }

    Handlebars.registerHelper('down_nav',function(cats){
        var rec = function(cats){
            var content = '';
            if(cats.length){
                content += '<ul>';
                for(var i in cats){
                    var child = cats[i];
                    content += '<li><a categoryid="'+child.id+'">'+child.name+'</a>'+rec(child.children)+'</li>';
                }
                content += '</ul>';
            }
            return content;
        }
        return rec(cats);
    });

    M.W.course_list.list = list = {

        /**
        * root category
        *
        * @type M.W.course_list.category
        *
        */
        cat_root:M.W.course_list.make_course_category(),

        /**
        * parase category navigation list
        *
        * @type Handlebars
        *
        */
        nav_li_template:Handlebars.compile($('#discovery-navigation-li').html()),

        /**
        *
        * parse course box item
        *
        * @type Handlebars
        *
        */
        course_box_template : Handlebars.compile($('#discover-courses-box-li').html()),

        /**
        *
        * parse course list item
        *
        * @type Handlebars
        *
        */
        course_list_template : Handlebars.compile($('#discover-courses-list-li').html()),

        /**
        * category's id or search;s keywords
        * @type int|string
        */
        current_cat_id : $.getUrlVar('categoryid')-0,

        /**
        * Paging tracker
        *
        * @type Object
        */
        paging:{
            max:null,
            min:null,
            current:($.getUrlVar('page') ? $.getUrlVar('page')-0 : null)
        },

        /**
        * I'm searching.
        *
        * @type Boolean
        */
        search:false,

        /**
        * course map
        * key courseid
        * value course info
        *
        * @type Object
        */
        courses : {},

        my_courses:$.parseJSON($('.my-courses-json').html()),

        /**
        * True if i enrolled the course
        *
        * @param {int} courseid
        * @returns {bool}
        */
        course_enrolled:function(courseid){
            return list.my_courses[courseid] ? true : false;
        },

        /**
        * Refresh the course list
        * Search or category's
        *
        * @param refresh
        */
        refresh_course_list:function(refresh){
            if( !list.current_cat_id){
                return;
            }

            /**
            * parse one course list
            *
            * @param {int} i order
            * @param {string} coursebox course html wrapper
            */
            var display_course_list = function(i,coursebox){
                var  course = {};
                course.line_last = (i%4 == 3);
                course.order = i;
                course.name = $(coursebox).find('.info>.name>a').text();
                course.url = $(coursebox).find('.info>.name>a').attr('href');
                course.id = $.getUrlVar('id',course.url);
                course.enrolled = list.course_enrolled(course.id);
                course.enrols = [];
                course.enrol_self = false;
                $(coursebox).find('.enrolmenticons img').each(function(ei,en){
                    var component = $.getUrlVar('component',$(en).attr('src'));
                    var image = $.getUrlVar('image',$(en).attr('src'));
                    course.enrols.push([component,image]);
                    if(component === 'enrol_self'){
                        course.enrol_self = image;
                    }
                });
                course.can_enrol = !course.enrolled && course.enrol_self;
                course.summary = $(coursebox).find('.content>.summary').text();
                course.cover = $(coursebox).find('.content>.courseimage>img').attr('src');
                if(!course.cover){
                    course.cover = M.cfg.wwwroot+
                    '/theme/image.php?theme=udemy&component=theme&image=cover-default';
                }
                course.teachers = [];
                $(coursebox).find('.content>.teachers>li').each(function(ti,tn){
                    course.teachers.push({text:$(tn).text(),url:$(tn).find('a').attr('href')});
                });
                course.cats = [];
                $(coursebox).find('.content>.coursecat>a').each(function(ci,cn){
                    course.cats.push({
                        name:$(cn).text(),
                        categoryid:$.getUrlVar('categoryid',$(cn).attr('href'))});
                });
                //search ?
                $('.discover-courses-list').append(
                    list.search ? list.course_list_template(course) : list.course_box_template(course) );
                //store course
                list.courses[course.id] = course;
            };

            //clear navigation
            if($('.discovery-navigation>li').length>1){
                var n_l = $('.discovery-navigation>li').length;
                $('.discovery-navigation>li').each(function(i,n){
                    if(i<n_l-1){
                        $(n).remove();
                    }
                });
            }
            //show loading
            list.toggle_loading(true);
            if(!list.search){

                //navigaiton
                var current_cat = M.W.course_list.find_course_category(list.cat_root, list.current_cat_id);
                $('.discovery-navigation').prepend($($.trim(list.nav_li_template(current_cat))).addClass('on'));
                var parent_cat = current_cat;
                while(parent_cat = parent_cat.parent){
                    $('.discovery-navigation').prepend(list.nav_li_template(parent_cat));
                }

                if(!refresh){
                    //from page self
                    $('.coursebox').each(display_course_list);
                    list.parse_paging($('.paging:not(.paging-morelink)').html());
                    list.toggle_loading(false);
                }else{
                    //from server
                    var page_num = list.paging.current === null ? 0 : list.paging.current;
                    var params = {categoryid:list.current_cat_id,browse:'courses',page:page_num};
                    var url = M.cfg.wwwroot+'/course/index.php';
                    //change brower location
                    $.replaceBrowserUrlWithParams('',url,params);
                    params.ajax = 'ajax';
                    $.get(url,params,function(data){
                        $(data).find('.coursebox').each(display_course_list);
                        if(list.paging.current === null ){
                            //parse paging
                            list.parse_paging($(data).find('.paging:not(.paging-morelink)').html());
                        }
                        //calculate paging
                        list.toggle_loading(false);
                    });
                }
            }else{

                //navigation
                $('.discovery-navigation').prepend(list.nav_li_template(list.cat_root));
                //search what?
                $('.results-for').removeClass('none').find('.term').text(list.current_cat_id);

                var url = M.cfg.wwwroot+ '/course/search.php';
                var page_num = list.paging.current === null ? 0 : list.paging.current;
                var params = {search:list.current_cat_id,page:page_num};
                //change brower location
                {
                    var bp = params;
                    bp.browse = 'courses';
                    $.replaceBrowserUrlWithParams('',M.cfg.wwwroot+'/course/index.php',bp);
                }
                $.get(url,params,function(data){
                    if(!$(data).find('.coursebox').length){
                        //nothing
                        $('#no-courses').removeClass('none').html(
                            M.util.get_string('nocoursesfound','core',list.current_cat_id));
                        list.toggle_paging(true);
                    }else{
                        //something
                        $(data).find('.coursebox').each(display_course_list);
                        if(list.paging.current === null
                            || ($('#discover-search-box').attr('click_by_browser')-0) ){

                            //If submit comes from browser.
                            $('#discover-search-box').attr('click_by_browser',0);

                            //parse paging
                            list.parse_paging($(data).find('.paging:not(.paging-morelink)').html());
                        }
                    }
                    //calculate paging
                    list.toggle_loading(false);
                });

            }
        },

        /**
        * create paging button
        * use <ul class="paging"/>
        *
        * @param {string} paging_html
        */
        parse_paging:function(paging_html){
            list.toggle_paging(true);
            if( !$(paging_html).find('a.current').length ){
                return;
            }
            list.paging.current = $(paging_html).find('a.current').html()-1;
            if($(paging_html).find('.last').length){
                list.paging.max = $.getUrlVar('page',$(paging_html).find('.last').attr('href'));
            }else if($(paging_html).find('.next').length){
                var tmpl = $(paging_html).find('a').length;
                list.paging.max = $.getUrlVar('page',$($(paging_html).find('a').get(tmpl-2)).attr('href'));
            }else{
                list.paging.max = list.paging.current;
            }
            if($(paging_html).find('.first').length){
                list.paging.min = $.getUrlVar('page',$(paging_html).find('.first').attr('href'));
            }else if($(paging_html).find('.previous').length){
                list.paging.min = $.getUrlVar('page',$($(paging_html).find('a').get(1)).attr('href'));
            }else{
                list.paging.min = list.paging.current;
            }
            list.toggle_paging(false);
        },

        /**
        * hide one or two paging button
        * or nothing
        * When current eq max, hide next.
        * When current ew min, hide prev.
        *
        * @param {bool} hide
        */
        toggle_paging:function(hide){
            if(hide){
                $('.course-nav-btn').addClass('none');
            }else{
                $('.course-nav-btn').removeClass('none');
                if(list.paging.min == list.paging.current){
                    $('.course-nav-btn.prev').addClass('none');
                }
                if(list.paging.max == list.paging.current){
                    $('.course-nav-btn.next').addClass('none');
                }
            }
        },
        /**
        * show or hide loading picture
        *
        * @param {bool} show
        */
        toggle_loading:function(show){
            if(show){
                $('.results-for').addClass('none');
                $('#no-courses').addClass('none');//search course: no course hint
                $('.discover-courses-list').html('');//when show loading,we need clear course list html.
                $('#ud-discover>.container>.ajax-loader-stick').removeClass('none');
            }else{
                $('#ud-discover>.container>.ajax-loader-stick').addClass('none');
            }
        },
        init:function(){

            //prev or next button
            $(document).on('click','.course-nav-btn.prev,.course-nav-btn.next',function(e){
                var is_prev = $(e.currentTarget).hasClass('prev');
                if((is_prev && list.paging.current == list.paging.min) ||
                    (!is_prev && list.paging.current == list.paging.max)){
                    return;
                }
                if(is_prev){
                    list.paging.current--;
                }else{
                    list.paging.current++;
                }
                list.toggle_paging(false);//hide some paging button
                list.refresh_course_list(true);//from ajax
            })
            //search form
            .on('submit','#discover-search-box',function(e){
                e.preventDefault();
                list.toggle_paging(true);
                list.search = true;
                list.current_cat_id = $(this).find('input[type=text]').val();
                if($(this).attr('click_by_browser')-0){
                    //mark as brower, may be we need paging current.
                    //the search contents are not from course/index.php, but from course/search.php
                    //So there are not course boxes. must get them from server by ajax.
                }else{
                    //when submit by user click, no paging.current.
                    list.paging.current = null;
                }
                list.refresh_course_list(true);//from ajax
            })
            //choose a category
            .on('click','.discovery-navigation .ddown>ul a[categoryid]',function(e){
                var categoryid = $(this).attr('categoryid')-0;
                if(categoryid){
                    list.toggle_paging(true);
                    list.search = false;
                    list.current_cat_id = categoryid;
                    list.paging.current = null;
                    list.refresh_course_list(true);//from ajax
                }
            });

            if($.getUrlVar('search')){
                //search course
                $('#discover-search-box input[type=text]').val($.getUrlVar('search'));
                $('#discover-search-box').attr('click_by_browser','1');//mark as brower
                $('#discover-search-box').submit();
            }else{
                //a categoryid
                list.refresh_course_list(false);//from page self
            }

        }
    }

    list.init();
});