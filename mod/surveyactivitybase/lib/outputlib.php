<?php

//wmios is mine.
namespace wmios\survey;

use \moodle_page;

/**
* When you need a survey standard layout ,Use this renderer.
* It's survey base renderer.
* In activity code,Must extend this.
* Use static::header and static::footer output layout.
*
*/
abstract class plugin_renderer_base extends \plugin_renderer_base{

    /**
    * layout header
    *
    * @var \String
    */
    protected $_layout_header = null;

    /**
    * layout footer
    *
    * @var \String
    */
    protected $_layout_footer = null;

    /**
    * use for smarty file
    *
    * @var \Smarty
    */
    protected $_smarty = null;

    /**
    *
    *
    * @param \moodle_page $page
    * @param \String $target
    * @return self
    */
    public function __construct(moodle_page $page, $target) {
        global $CFG,$PAGE;

        $class_name = get_class($this);

        $template_dir = $CFG->dirroot.'/mod/surveyactivitybase/view';
        if(substr($class_name,0,strlen('surveyactivity_')) === 'surveyactivity_'){
            $component = substr($class_name,strlen('surveyactivity_'));
            $component = substr($component,0,strlen($component)-strlen('_renderer'));
            $template_dir = $CFG->dirroot.'/mod/surveyactivitybase/activity/'.$component.'/view';
        }

        $this->_smarty = \get_smarty(null,$template_dir);
        parent::__construct($page, $target);
    }

    /**
    * @return Smarty
    *
    */
    public function get_smarty()
    {
        return $this->_smarty;
    }

    /**
    * Render layout
    *
    * the result will be cached.
    *
    * @param bool $force
    */
    protected function render_layout($force = false){
        global $PAGE;
        static $inited = false;
        if(!$inited || $force){

            $smarty = $this->_smarty;
            $old_template_dir = $smarty->getTemplateDir();
            $old_config_dir = $smarty->getConfigDir();

            $smarty->setTemplateDir(dirname(__FILE__).'/../view/scripts/'.$PAGE->theme->name.'/');
            $smarty->setConfigDir(dirname(__FILE__).'/../view/configs/');

            $placeholder = $this->layout_main_content_placeholder();
            $smarty->assign('maincontent',$placeholder);

            //navigation
            $nav = tool::get_nav();
            $smarty->assign('nav',$nav);

            $smarty->assign('base',tool::get_surveyactivity_base());

            $str = new \stdClass();
            $str->navigation = get_string('navigation',SURVEYACTIVITYBASE_PLUGIN_NAME);
            $smarty->assign('str',$str);

            $url = new \stdClass();
            $url->add_activity = new \moodle_url('/mod/surveyactivitybase/edit.php');
            $smarty->assign('url',$url);

            $url_base = new \stdClass();
            $tmp = new \moodle_url('/mod/surveyactivitybase/');
            $url_base->suveractivitybase = $tmp->out(false);
            $smarty->assign('url_base',$url_base);


            $rendered = $smarty->fetch('layout.tpl');

            $cutpos = strpos($rendered, $placeholder);
            if ($cutpos === false) {
                throw new \coding_exception('survey page layout file ' . $layoutfile . ' does not contain the main content placeholder, please include "<?php echo $OUTPUT->main_content() ?>" in theme layout file.');
            }

            $this->_layout_header = substr($rendered, 0, $cutpos);
            $this->_layout_footer = substr($rendered, $cutpos + strlen($placeholder));
            $inited = true;
            $smarty->setTemplateDir($old_template_dir);
            $smarty->setConfigDir($old_config_dir);
            $smarty->clearAssign('maincontent');
            $smarty->clearAssign('nav');
            $smarty->clearAssign('str');
            $smarty->clearAssign('url');
        }
    }

    /**
    * use for layout's place holder
    *
    */
    protected function layout_main_content_placeholder(){
        return '[]//?sasiJJJJJJ';
    }


    /**
    * Output survey layout's header
    * Can only be called once
    *
    *
    * HTML include:
    *   <<<this header
    *   <<<you content
    *   <<<this footer
    *
    */
    public function header(){

        static $rendered = false;
        if($rendered){
            throw new \Exception('Has rendered survey header');
        }

        $this->page->requires->js('/mod/surveyactivitybase/js/default.js');

        $outputheader = $this->output->header();
        $this->render_layout();
        $rendered = true;

        return $outputheader . $this->_layout_header;
    }

    /**
    * Output survey layout's footer
    * Can only be called once
    *
    *
    * HTML include:
    *   <<<this header
    *   <<<you content
    *   <<<this footer
    *
    */
    public function footer(){
        static $rendered = false;
        if($rendered){
            throw new \Exception('Has rendered survey footer');
        }

        $outputfooter = $this->output->footer();
        $this->render_layout();
        $rendered = true;

        return $this->_layout_footer.$outputfooter ;
    }
}