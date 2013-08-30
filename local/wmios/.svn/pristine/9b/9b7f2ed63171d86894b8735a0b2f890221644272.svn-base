<?php

/**
* @see plugin_renderer_base
*/
class local_wmios_renderer extends plugin_renderer_base {

    /**
    * Get html for login page
    * @see /local/wmios/login/index.php
    * 
    * @param int $errorcode
    * @return string
    */
    public function login_page($errorcode = 0){
        return <<<EOD
        <form action="/login/" method="post">
        <input type="text" name="username" />
        <input type="password" name="password" />
        <input type="submit" value="submit" />
        </form>
EOD;
    }

}