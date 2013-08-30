<?php

defined('MOODLE_INTERNAL') || die();

class mod_dekiwiki_renderer extends plugin_renderer_base {
    /**
    * smarty class
    *
    * @var Smarty
    */
    protected $_smarty = null;

    /**
    * @return Smarty
    *
    */
    public function get_smarty() {
        if($this->_smarty === null){
            $this->_smarty = get_smarty(DEKIWIKI_PLUGIN_NAME,null,$this->page);
            $url = new moodle_url('/mod/dekiwiki');
            $this->_smarty->assign('url_mod_dekiwiki',$url->out(false));
        }
        return $this->_smarty;
    }

}
