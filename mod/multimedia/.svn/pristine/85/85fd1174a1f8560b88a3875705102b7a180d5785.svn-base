<?php
global $CFG;
require_once ($CFG->dirroot.'/local/kaltura/lib.php');

class multimedia_video extends multimedia_base{
    public function format_unit($num){
        return wmios_format_second_to_minute($num);
    }
    
    /**
    * 
    * 
    * @param mixed $content
    * @return bool
    */
    public static function contain_multimedia($content){
        
        return static::parse_kaltura_entry_id($content) ? true : false;
    }
    
    /**
    * @see filter_kaltura::filter()
    * 
    * @param mixed $content
    * @return string the kaltura entry id
    */
    protected static function parse_kaltura_entry_id($content){
        global $CFG;
        
        
        if (!is_string($content) or empty($content)) {
            return '';
        }
        
        if (stripos($content, '</a>') === false) {
            return '';
        }
        
        $uri = local_kaltura_get_host();
        $uri = rtrim($uri, '/');
        $uri = str_replace(array('.', '/', 'https'), array('\.', '\/', 'https?'), $uri);

        $search = '/<a\s[^>]*href="('.$uri.')\/index\.php\/kwidget\/wid\/_([0-9]+)\/uiconf_id\/([0-9]+)\/'.
                'entry_id\/([\d]+_([a-z0-9]+))\/v\/flash"[^>]*>([^>]*)<\/a>/is';
        $results = array();
        preg_match_all (  $search,  $content, $results);//duration
        if(!isset($results[4][0]) || !is_string($results[4][0])){
            return '';
        }
        if( local_kaltura_get_ready_entry_object($results[4][0],false) ){
            return $results[4][0];
        }else{
            return '';
        }
        
    }
    
    public static function parse_multimedia_length($content){
        $entry_id = static::parse_kaltura_entry_id($content);
        $duration = -1;
        if($entry_id && $entry_obj = local_kaltura_get_ready_entry_object($entry_id,false)){
            $duration = $entry_obj->duration;
        }
        return $duration;
    }
}
