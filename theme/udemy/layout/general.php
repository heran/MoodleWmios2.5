<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar() && $COURSE!=$SITE) || true;
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT) && (empty($PAGE->layout_options['noblocks']));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
$bodyclasses[] = 'theme-udemy';
if ($showsidepost) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost) {
    $bodyclasses[] = 'content-only';
}

if ($hascustommenu) {
    $bodyclasses[] = 'has-custom-menu';
}

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
    if (empty($PAGE->layout_options['nocoursefooter'])) {
        $coursecontentfooter = $OUTPUT->course_content_footer();
        $coursefooter = $OUTPUT->course_footer();
    }
}

/**
* when nothing print ,we need call some mods and  blocks before print header
*/
theme_udemy_before_print_header($PAGE);

echo $OUTPUT->doctype();
?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>" courseid="<?php echo isset($COURSE->id) ? $COURSE->id : 0;?>">
    <?php echo $OUTPUT->standard_top_of_body_html() ?>
    <div id="page">
        <div id="wrapper">
            <!-- START OF HEADER -->
            <?php if ($hasheading || $hasnavbar || !empty($courseheader) || !empty($coursefooter)) { ?>

                <header>
                    <div class="container header-container strict ">

                        <?php
                            if(isloggedin()){
                                $userpicture = new user_picture($USER);
                            ?>
                            <div class="ddown user" style="float: left;">
                                <a href="javascript:void(0)" class="graylink">
                                    <img class="bordered-thumb" src="<?php echo $userpicture->get_url($PAGE, $OUTPUT)->out();?>">
                                    <i class="ellipsis"><?php echo fullname($USER, true);?></i>
                                </a>
                                <?php echo $OUTPUT->header_profile();?>

                            </div>
                            <?php
                                //Some day We need notifications
                            ?>
                            <!--<div class="ud-notifications user ddown notifications-wrapper popup-notifications right-bordered left-bordered">
                            <a href="javascript:void(0)" title="<?php echo get_string('global_navigation','theme_udemy');?>"><span class="none count">0</span></a>
                            </div>-->
                            <?php
                                if(has_capability('moodle/site:config',context_system::instance())){
                                ?>
                                <div class="user ddown" style="float: left;">
                                    <a href="javascript:void(0)" class="graylink">
                                        <i class="ellipsis"><?php echo get_string('settings');?></i>
                                    </a>
                                    <?php echo $OUTPUT->header_settingsnav();?>
                                </div>
                                <?php
                                }
                            ?>
                            <a class="top-link-my-courses graylink" href="<?php echo $CFG->wwwroot?>/my/"><?php echo get_string('mycourses')?></a>
                            <a id="logo" href="<?php echo $CFG->wwwroot?>">Wmios Logo</a>
                            <div class="ud-search">
                                <form id="searchbox" action="<?php echo $CFG->wwwroot?>/course/index.php">
                                    <input type="text" placeholder="<?php echo get_string('searchcourses')?>" autocomplete="off" name="search" id="quick-search" class="ui-autocomplete-input" role="textbox" aria-autocomplete="list" aria-haspopup="true">
                                    <input type="submit">
                                </form>
                            </div>
                            <a class="top-link-course-list graylink"  style="float: right;" href="<?php echo $CFG->wwwroot?>/course/"><?php echo get_string('listofcourses')?></a>
                            <?php
                                if($PAGE->button){?>
                                <div class="top-link-header-button graylink" style="float: right;">
                                    <?php echo $PAGE->button; ?>
                                </div>
                                <?php
                                }
                                $my_app_node = get_my_app_nav();
                                if($my_app_node ->has_children()){
                                ?>
                                <div class="my-app-nav-box user ddown down-right" style="float: right;">
                                    <a href="javascript:void(0)" class="graylink">
                                        <i class="ellipsis"><?php echo get_string('my_application',LOCAL_WMIOS_PLUGIN_NAME);?></i>
                                    </a>
                                    <ul>
                                        <?php
                                            foreach($my_app_node->children as /* @var navigation_node*/$child){
                                                echo "<li><a href=\"{$child->action->out()}\">{$child->get_content(true)}</a></li>";
                                            }
                                        ?>
                                    </ul>
                                </div>
                                <?php
                                }
                                if($COURSE!=$SITE && $current_course_node = $OUTPUT->get_current_course_nav()){
                                ?>
                                <div class="user ddown down-right current-course" style="float: right;">
                                    <a href="javascript:void(0)" class="graylink">
                                        <i class="ellipsis"><?php echo get_string('currentcourse','theme_udemy');?></i>
                                    </a>
                                    <?php echo $OUTPUT->nav_node($current_course_node);?>
                                </div>
                                <?php
                                }
                                //When In mod or activiry ,we need it's navigation
                                $activity_node = null;
                                if($PAGE->context->contextlevel === CONTEXT_MODULE){
                                    $activity_node = $OUTPUT->get_current_module_nav($PAGE->context);
                                }
                                if(!$activity_node && $PAGE->cm){
                                    $activity_node = $OUTPUT->get_current_module_nav(context_module::instance($PAGE->cm->id));
                                }
                                if($activity_node){
                                ?>
                                <div class="user ddown down-right current-module" style="float: right;">
                                    <a href="javascript:void(0)" class="graylink">
                                        <i class="ellipsis"><?php echo $activity_node->get_content();?></i>
                                    </a>
                                    <?php echo $OUTPUT->nav_node($activity_node);?>
                                </div>
                                <?php
                                }
                            }else{
                            ?>
                            <a id="logo" href="<?php echo $CFG->wwwroot?>">Wmios Logo</a>
                            <?php
                            }
                        ?>
                    </div>
                </header>

                <?php }?>
            <!-- END OF HEADER -->

            <?php /* if ($hasnavbar) { ?>
                <div class="navbar">
                <div class="navbar-wrapper clearfix">
                <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                </div>
                </div>
            <?php } */?>

            <!-- START OF CONTENT -->
            <div id="page-content" class="page-content main container page-content-<?php p($PAGE->bodyid) ?>">
                <?php
                    if (!empty($courseheader)) {
                    ?>
                    <div class="course-header"><?php echo $courseheader; ?></div>
                    <?php
                    }
                    echo $OUTPUT->course_info_header();

                ?>
                <div id="region-main-box" class="main-content">
                    <div id="region-main" class="<?php if($hassidepost){echo 'left-col';}?>">
                        <div class="region-content">

                            <?php
                                echo $coursecontentheader;
                                echo $OUTPUT->main_content();
                                echo $coursecontentfooter;
                            ?>
                        </div>
                    </div>
                    <?php if($hassidepost){
                        ?>
                        <div class="right-col block-region" id="region-post">
                            <div class="region-content">
                                <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                            </div>
                        </div>
                        <?php
                        }
                    ?>
                </div>
            </div>
            <!-- END OF CONTENT -->



            <!-- START OF FOOTER -->
            <?php if (!empty($coursefooter)) { ?>
                <div id="course-footer"><?php echo $coursefooter; ?></div>
                <?php } ?>

            <?php if ($hasfooter) { ?>
                <div id="page-footer">
                    <div id="page-footer-wrapper">
                        <div class="left"><p>&copy;<?php echo date('Y');?> WMIOS </p></div>
                        <!--<div class="right">
                        <p>
                        <a href="/"><?php echo get_string('gohome','theme_udemy'); ?></a>
                        <a href="/"><?php echo get_string('aboutus','theme_udemy'); ?></a>
                        <a href="/"><?php echo get_string('contactus','theme_udemy'); ?></a>
                        <a href="/"><?php echo get_string('helpme','theme_udemy'); ?></a>
                        </p>
                        </div>-->
                        <?php echo $OUTPUT->page_bottom_help_button();?>
                        <?php echo $OUTPUT->standard_footer_html();?>
                    </div>
                </div>
                <?php } ?>
            <!-- end OF FOOTER -->


        </div>

    </div>
    <?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>

