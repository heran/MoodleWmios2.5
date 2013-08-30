<div id="ud-discover">
    <div class="top course-discover-top">
        <h1 class="thin">{get_string('listofcourses')}</h1>
        <nav class="course-discover-nav">
            <script id="discovery-navigation-li" type="text/x-handlebars-template">
            {literal}
                <li class="categories-col ddown-col">
                    <div class="ddown">
                        <a categoryid="{{id}}">{{name}}</a>
                        {{#down_nav children}}{{/down_nav}}
                    </div>
                </li>
            {/literal}
            </script>
            <ul class="gray-nav discovery-navigation">
                <li class="search-col">
                    <form id="discover-search-box" class="search-box">
                        <input placeholder="{get_string('searchcourses')}" type="text">
                        <input type="submit">
                    </form>
                </li>
            </ul>
            {*
            <div id="filters" class="ddown none">
                <a href="">Filters</a>
                <div>
                    <h3>Sort By</h3>
                    <div class="tab-container sort-container">
                        <div class="tab-label-container no-content">
                            <ul class="gray-nav dark-nav">
                                <li style="">
                                    <input checked="checked" name="sort" id="popularity" type="radio">
                                    <label for="popularity">Popularity</label>
                                </li>
                                <li style="">
                                    <input checked="checked" name="sort" id="reviews" type="radio">
                                    <label for="reviews">Reviews</label>
                                </li>
                                <li style="">
                                    <input name="sort" id="newest" type="radio">
                                    <label for="newest">Newest</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h3>Price</h3>
                    <div class="tab-container price-container">
                        <div class="tab-label-container no-content">
                            <ul class="gray-nav dark-nav">
                                <li style="">
                                    <input checked="checked" id="price-free-btn" name="price" type="radio">
                                    <label for="price-free-btn">Free</label>
                                </li>
                                <li style="">
                                    <input id="price-paid-btn" name="price" type="radio">
                                    <label for="price-paid-btn">Paid</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h3 class="category-text">Category</h3>
                    <div class="gray-dropdown-wrapper category-filter-wrapper">
                        <div class="gray-dropdown category">
                            <ul>
                                <li style="" data-category-name="All Categories" data-category-id="0" class="selected-category-filter">
                                    <input value="All Categories" type="radio">
                                    <label for="s0">All Categories</label>
                                </li>
                                <li style="" data-category-name="Technology" data-category-id="267">
                                    <input value="Technology" type="radio">
                                    <label for="s0">Technology</label>
                                </li>
                                <li style="" data-category-name="Business" data-category-id="268">
                                    <input value="Business" type="radio">
                                    <label for="s0">Business</label>
                                </li>
                                <li style="" data-category-name="Design" data-category-id="269">
                                    <input value="Design" type="radio">
                                    <label for="s0">Design</label>
                                </li>
                                <li style="" data-category-name="Arts and Photography" data-category-id="273">
                                    <input value="Arts and Photography" type="radio">
                                    <label for="s0">Arts and Photography</label>
                                </li>
                                <li style="" data-category-name="Health and Fitness" data-category-id="276">
                                    <input value="Health and Fitness" type="radio">
                                    <label for="s0">Health and Fitness</label>
                                </li>
                                <li style="" data-category-name="Lifestyle" data-category-id="274">
                                    <input value="Lifestyle" type="radio">
                                    <label for="s0">Lifestyle</label>
                                </li>
                                <li style="" data-category-name="Math and Science" data-category-id="271">
                                    <input value="Math and Science" type="radio">
                                    <label for="s0">Math and Science</label>
                                </li>
                                <li style="" data-category-name="Education" data-category-id="277">
                                    <input value="Education" type="radio">
                                    <label for="s0">Education</label>
                                </li>
                                <li style="" data-category-name="Languages" data-category-id="279">
                                    <input value="Languages" type="radio">
                                    <label for="s0">Languages</label>
                                </li>
                                <li style="" data-category-name="Humanities" data-category-id="272">
                                    <input value="Humanities" type="radio">
                                    <label for="s0">Humanities</label>
                                </li>
                                <li style="" data-category-name="Social Sciences" data-category-id="270">
                                    <input value="Social Sciences" type="radio">
                                    <label for="s0">Social Sciences</label>
                                </li>
                                <li style="" data-category-name="Music" data-category-id="278">
                                    <input value="Music" type="radio">
                                    <label for="s0">Music</label>
                                </li>
                                <li style="" data-category-name="Crafts and Hobbies" data-category-id="275">
                                    <input value="Crafts and Hobbies" type="radio">
                                    <label for="s0">Crafts and Hobbies</label>
                                </li>
                                <li style="" data-category-name="Sports" data-category-id="280">
                                    <input value="Sports" type="radio">
                                    <label for="s0">Sports</label>
                                </li>
                                <li style="" data-category-name="Games" data-category-id="281">
                                    <input value="Games" type="radio">
                                    <label for="s0">Games</label>
                                </li>
                                <li style="" data-category-name="Other" data-category-id="282">
                                    <input value="Other" type="radio">
                                    <label for="s0">Other</label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            *}
        </nav>

        <div class="results-for none" style="text-align:center; font-size:14px; margin:5px 0px;">
            <span class="results">{get_string('search_keywords','theme_udemy')}: </span>
            <b><span class="term"></span></b>
        </div>
        {*
        <ul id="selected-filters" class="none">


            <li data-filtertype="Sort">



                Sort:
                <b>Reviews</b>

                <i></i>
            </li>

            <li data-filtertype="Price">


                Price:
                <b>Free</b>


                <i></i>
            </li>


        </ul>
        *}
    </div>
    {*
    <div class="slider-container none">
        <div class="ud-slider slider" data-loop="true" data-auto_slider="10000" data-slide_time="1000">
            <ul style="left: -300%;" class="slide-this">

                <li class="b" data-category="course" data-id="51671" style="background: none repeat scroll 0% 0% rgb(243, 156, 61);">
                    <a href="https://www.udemy.com/welch-way-managing-change/?dtcode=lgu56f98">
                        <img src="https://www.udemy.com/static/images/featured-banners/1-welch-way-managing-change-jack.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="course" data-id="44267" style="background: none repeat scroll 0% 0% rgb(247, 150, 45);">
                    <a href="https://www.udemy.com/how-to-negotiate-salary-negotiating-a-raise-or-promotion/">
                        <img src="https://www.udemy.com/static/images/featured-banners/2.how-to-negotiate-salary-negotiating-a-raise-or-promotion.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="link" style="background: none repeat scroll 0% 0% rgb(237, 237, 237);">
                    <a href="http://neverstoplearning.udemy.com/jay-salton/">
                        <img src="https://www.udemy.com/static/images/featured-banners/3-jay-story.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="course" data-id="24877" style="background: none repeat scroll 0% 0% rgb(30, 123, 190);">
                    <a href="https://www.udemy.com/introductory-financial-accounting/?dtcode=2bskscqu">
                        <img src="https://www.udemy.com/static/images/featured-banners/4-introductory-financial-accounting.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="course" data-id="20387" style="background: none repeat scroll 0% 0% rgb(12, 61, 128);">
                    <a href="https://www.udemy.com/eatmystrings-guitar-insanity-workout/">
                        <img src="https://www.udemy.com/static/images/featured-banners/5.eatmystrings-guitar-insanity-workout.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="course" data-id="17029" style="background: none repeat scroll 0% 0% rgb(26, 113, 216);">
                    <a href="https://www.udemy.com/the-black-arts-of-persuasion-for-startups/">
                        <img src="https://www.udemy.com/static/images/featured-banners/6.the-black-arts-of-persuasion-for-startups.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="course" data-id="36311" style="background: none repeat scroll 0% 0% rgb(237, 237, 237);">
                    <a href="https://www.udemy.com/designing-for-engagement/">
                        <img src="https://www.udemy.com/static/images/featured-banners/7.designing-for-engagement.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="b" data-category="course" data-id="23887" style="background: none repeat scroll 0% 0% rgb(217, 57, 65);">
                    <a href="https://www.udemy.com/portrait-photography-with-simple-gear/">
                        <img src="https://www.udemy.com/static/images/featured-banners/8.portrait-photography-with-simple-gear.jpg">
                        <div class="wrapper none">
                            <div class="container">
                                <div class="table">
                                    <div class="cell none">
                                        <h1>Description</h1>
                                        <h2>Description</h2>
                                        <h3>Description</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

            </ul>
            <ul class="bullets"><li style="" class=""></li><li class="" style=""></li><li class="" style=""></li><li class="on" style=""></li><li class="" style=""></li><li class="" style=""></li><li class="" style=""></li><li class="" style=""></li></ul>
            <nav>
                <div class="container">
                    <span class="prev on"></span>
                    <span class="next on"></span>
                </div>
            </nav>
        </div>
    </div>
    <ul class="none" id="collection-thumbs">

        <li style="">
            <a href="https://www.udemy.com/collection/all-about-mobile-development2">
                <img src="https://udemy-images.s3.amazonaws.com/collection/160x90/2006_6aca_4.jpg" alt="">
                <span>
                    <span>View the collection</span>
                </span>
            </a>
            <b>
                14 Courses                </b>
        </li>

        <li style="">
            <a href="https://www.udemy.com/collection/all-about-marketing">
                <img src="https://udemy-images.s3.amazonaws.com/collection/160x90/2004_09a2.jpg" alt="">
                <span>
                    <span>View the collection</span>
                </span>
            </a>
            <b>
                16 Courses                </b>
        </li>

        <li style="">
            <a href="https://www.udemy.com/collection/web-development-courses">
                <img src="https://udemy-images.s3.amazonaws.com/collection/160x90/2032_97b3_3.jpg" alt="">
                <span>
                    <span>View the collection</span>
                </span>
            </a>
            <b>
                21 Courses                </b>
        </li>

        <li style="">
            <a href="https://www.udemy.com/collection/adobe-cs6">
                <img src="https://udemy-images.s3.amazonaws.com/collection/160x90/2010_f262_2.jpg" alt="">
                <span>
                    <span>View the collection</span>
                </span>
            </a>
            <b>
                10 Courses                </b>
        </li>

        <li style="">
            <a href="https://www.udemy.com/collection/learn-to-design-everything">
                <img src="https://udemy-images.s3.amazonaws.com/collection/160x90/2005_ca32_3.jpg" alt="">
                <span>
                    <span>View the collection</span>
                </span>
            </a>
            <b>
                19 Courses                </b>
        </li>

    </ul>
    *}

    <div class="container fullscreen-courses">

        {*<ul id="courses" class="discover-courses-list multi-line none">
        </ul>*}
        <div id="discover-courses-rows">
            <div class="ud-coursecarousel course-list-wrapper collection fullscreen-courses" data-number-of-courses="10">
                <div class="courses-header">
                    {*<div class="left-items">
                        <h3>Similar to</h3>
                        <img src="https://udemy-images.s3.amazonaws.com/course/48x27/39115_0e6d_6.jpg">
                        <span class="title fitted ellipsis">Java Design Patterns and Architecture</span>
                    </div>*}
                    <div class="right-items">
                        <div class="nav-container">
                            <a href="javascript:void(0)" class="course-nav-btn prev btn">
                                <i class="icon-chevron-left"></i>
                            </a>
                            <a href="javascript:void(0)" class="course-nav-btn next btn">
                                <i class="icon-chevron-right"></i>
                            </a>
                        </div>
                        {*<a class="collapse-btn view-all btn" href="javascript:void(0)">View All</a>*}
                    </div>
                </div>
                <div class="discover-courses-list-mask">
                    <script id="discover-courses-list-li" type="text/x-handlebars-template">
                    {literal}
                    <li class="course-item-list">
                        <a href="{{url}}" target="_blank">
                            <span class="thumb">
                                <img src="{{cover}}">
                            </span>
                            <div class="details">
                                <span class="title ellipsis">{{name}}</span>
                                <span class="ins ellipsis">{{#each teachers}}{{text}}{{/each}}</span>
                                <div class="desc ellipsis">{{summary}}</div>
                                <div class="bottom">
                                    <i class="people-icon sp none"></i>
                                    <span class="count none"> <b>265</b> students </span>
                                    <div class="rating-big-stars none">
                                        <span style="width: 90%"></span>
                                    </div>
                                    <span class="review-count none">(12)</span>
                                    <span class="lecture-info none">
                                        <i class="icon-time"></i> <b>32 hours</b> of content in <b>80 lectures</b>
                                    </span>
                                    <span class="">
                                        {/literal}{get_string('categories')}:{literal}
                                        {{#each cats}}<span categoryid="{{categoryid}}">{{name}}</span>{{/each}}
                                    </span>
                                </div>
                                {{#if can_enrol}}
                                <span class="price">{/literal}{get_string('enrol','enrol')}{literal}</span>
                                {{/if}}
                            </div>
                        </a>
                    </li>
                    {/literal}
                    </script>
                    <script id="discover-courses-box-li" type="text/x-handlebars-template">
                    {literal}
                    <li data-courseid="22169" class="course-box{{#if line_last}} line-last{{/if}}">

                        <a href="{{url}}" class="mask" target="_blank">
                            {{#if can_enrol}}
                            <div class="add-to-wishlist btn btn-small ud-wishlist" href="#" data-courseid="22169">
                                <span class="ajax-loader-stick wishlist-loader none"></span>
                                <i class="icon-plus"></i>
                                <span class="in-wishlist none">Wishlisted</span>
                                <span class="not-in-wishlist">{/literal}{get_string('enrol','enrol')}{literal}</span>
                            </div>
                            {{/if}}
                            <span class="course-thumb">
                                <img src="{{cover}}">
                            </span>
                            <span class="title">{{name}}</span>
                            <span class="details none">
                                <span class="price">
                                    Free
                                </span>
                                <span class="small-rating">
                                    <span style="width:92.5%"></span>
                                </span>
                                <span class="stu spb">
                                    3194
                                </span>
                            </span>
                            <span class="instructors">
                                <img class="ins-thumb none" src="">
                                <span class="r">
                                    <span class="ins-name ellipsis">
                                        {{#each teachers}}{{text}}{{/each}}
                                    </span>
                                    <span class="ins-job-title ellipsis none">

                                    </span>
                                </span>
                            </span>
                        </a>
                    </li>
                    {/literal}
                    </script>
                    <ul class="discover-courses-list multi-line">
                    </ul>
                </div>
            </div>
        </div>
        <div id="no-courses" class="none">

        </div>
        <div class="ajax-loader-stick none" style="margin-top: 20px;"></div>
        {*<div style="text-align: center; margin-top: 30px;">
            <a class="load-more btn none">
                More Courses …
            </a>
        </div>*}
    </div>
    <div class="my-courses-json none">
    {json_encode($mycourses)}
    </div>
</div>
