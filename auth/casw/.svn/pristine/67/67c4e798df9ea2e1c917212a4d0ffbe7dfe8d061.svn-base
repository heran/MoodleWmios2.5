<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/auth/cas/auth.php');
require_once($CFG->dirroot.'/auth/cas/CAS/CAS.php');

/**
* CAS authentication plugin.
*/
class auth_plugin_casw extends auth_plugin_cas {

    function __construct() {
        $this->authtype = 'casw';
        $this->roleauth = 'auth_casw';
        $this->errorlogtag = '[AUTH CASW] ';
        $this->init_plugin($this->authtype);
    }

    function get_title() {
        return get_string('pluginname', "auth_cas").'-wmios';
    }

    /**
    * Authentication choice (CAS or other)
    * Redirection to the CAS form or to login/index.php
    * for other authentication
    */
    function loginpage_hook() {
        global $frm;
        global $CFG;
        global $SESSION, $OUTPUT, $PAGE,$PHPCAS_CLIENT;

        //when cas server post a logout request.
        $this->process_logout_request();

        $site = get_site();
        $CASform = get_string('CASform', 'auth_cas');
        $username = optional_param('username', '', PARAM_RAW);

        if (!empty($username)) {
            if (isset($SESSION->wantsurl) && (strstr($SESSION->wantsurl, 'ticket') ||
                strstr($SESSION->wantsurl, 'NOCAS'))) {
                unset($SESSION->wantsurl);
            }
            return;
        }

        // Return if CAS enabled and settings not specified yet
        if (empty($this->config->hostname)) {
            return;
        }

        // Connection to CAS server
        $this->connectCAS();

        //first request: if the request has a ticket get param, we store it in cache by session_id.
        //when cas server log in.browser redirect to here.
        $this->store_tiket_in_temp_session();

        //second request: after first request. we get the ticket from the cache.
        //then store the current session_id with ticket.
        $this->store_session_id_by_ticket();

        if (phpCAS::checkAuthentication()) {
            $frm = new stdClass();
            $frm->username = phpCAS::getUser();
            $frm->password = 'passwdCas';
            return;
        }

        if (isset($_GET['loginguest']) && ($_GET['loginguest'] == true)) {
            $frm = new stdClass();
            $frm->username = 'guest';
            $frm->password = 'guest';
            return;
        }

        if ($this->config->multiauth) {
            $authCAS = optional_param('authCAS', '', PARAM_RAW);
            if ($authCAS == 'NOCAS') {
                return;
            }

            // Show authentication form for multi-authentication
            // test pgtIou parameter for proxy mode (https connection
            // in background from CAS server to the php server)
            if ($authCAS != 'CAS' && !isset($_GET['pgtIou'])) {
                $PAGE->set_url('/auth/cas/auth.php');
                $PAGE->navbar->add($CASform);
                $PAGE->set_title("$site->fullname: $CASform");
                $PAGE->set_heading($site->fullname);
                echo $OUTPUT->header();
                include($CFG->dirroot.'/auth/cas/cas_form.html');
                echo $OUTPUT->footer();
                exit();
            }
        }

        // Force CAS authentication (if needed).
        if (!phpCAS::isAuthenticated()) {
            phpCAS::setLang($this->config->language);
            //listen on the cas server's logout request.
            phpCAS::handleLogoutRequests(false,false);
            phpCAS::forceAuthentication();
        }
    }

    /**
    *
    * @see CASClient::isAuthenticated()
    *
    * when user login on cas server, the browser redirect here with the ticket.
    * CASClient::isAuthenticated() checked it, then store the cas user info in the temp session.
    * So, we store the ticket in the same temp session
    *
    *
    */
    protected function store_tiket_in_temp_session()
    {
        global $PHPCAS_CLIENT, $PHPCAS_WITH_TICKET;

        //store ticket-session _id
        $has_t = false;
        $ticket = null;
        switch($PHPCAS_CLIENT->getServerVersion())
        {
            case CAS_VERSION_1_0:
                $has_t = $PHPCAS_CLIENT->hasST();
                if($has_t)
                {
                    $ticket =  $PHPCAS_CLIENT->getST();
                }
                break;
            case CAS_VERSION_2_0:
                $has_t = $PHPCAS_CLIENT->hasPT();
                if($has_t)
                {
                    $ticket =  $PHPCAS_CLIENT->getPT();
                }
                break;
            case SAML_VERSION_1_1:
                $has_t = $PHPCAS_CLIENT->hasSA();
                if($has_t)
                {
                    $ticket =  $PHPCAS_CLIENT->getSA();
                }
                break;
        }
        if($has_t)
        {
            $_SESSION['PHPCAS_HAS_TICKET'] = $ticket;
            //when first request, we can't store the ticket
            $PHPCAS_WITH_TICKET = true;
        }
    }

    /**
    * We get the ticket from the temp session.
    * Store it in the global variable.
    * Then moodle login the cas user, regenerate the session_id.
    * When script shutdown, we store the new session_id with the ticket.
    *
    * SO, when cas server request to logout, we get it, and clear the session.
    *
    * @see CASClient::isAuthenticated()
    * @see complete_user_login()
    *
    */
    protected function store_session_id_by_ticket()
    {
        global $PHPCAS_TICKET_SESSION, $PHPCAS_WITH_TICKET;
        //when first request, we can't store the ticket
        //because the session_id is not the user's, but the cas'
        if($PHPCAS_WITH_TICKET || empty($_SESSION['PHPCAS_HAS_TICKET']))
        {
            return;
        }
        $PHPCAS_TICKET_SESSION = $_SESSION['PHPCAS_HAS_TICKET'];
        unset($_SESSION['PHPCAS_HAS_TICKET']);//Now we don't need this.
        register_shutdown_function(function(){
            global $PHPCAS_TICKET_SESSION;
            if($PHPCAS_TICKET_SESSION==null)
            {
                return;
            }
            //now the session_id belong to user on the browser.
            $cache = cache::make('auth_casw', 'ticket');
            $ticket = $cache->set($PHPCAS_TICKET_SESSION,session_id());
            $PHPCAS_TICKET_SESSION = null;
        });
    }

    /**
    * When cas server request to logout
    *
    */
    protected function process_logout_request()
    {
        if(empty($_POST['logoutRequest']))
        {
            return ;
        }
        //file_put_contents('c:\\2.txt',print_r($_REQUEST,true)."\r\n");

        //these code copy from phpCAS::handleLogoutRequests
        //get the session_id from cache by the ticket
        phpCAS::traceBegin();

        phpCAS::log("Logout requested");
        phpCAS::log("SAML REQUEST: ".$_POST['logoutRequest']);

        if(false)
        {
            //we can't always parase the ip to domain.
            $allowed_clients = array($this->config->hostname);
            $client_ip = $_SERVER['REMOTE_ADDR'];
            $client = gethostbyaddr($client_ip);
            file_put_contents('c:\\2.txt',print_r($this->config->hostname.'-'.$client.'-'.$client_ip,true)."\r\n");
            phpCAS::log("Client: ".$client."/".$client_ip);
            $allowed = false;
            foreach ($allowed_clients as $allowed_client) {
                if (($client == $allowed_client) or ($client_ip == $allowed_client)) {
                    phpCAS::log("Allowed client '".$allowed_client."' matches, logout request is allowed");
                    $allowed = true;
                    break;
                } else {
                    phpCAS::log("Allowed client '".$allowed_client."' does not match");
                }
            }
            if (!$allowed) {
                phpCAS::error("Unauthorized logout request from client '".$client."'");
                printf("Unauthorized!");
                phpCAS::traceExit();
                exit();
            }
        }

        // Extract the ticket from the SAML Request
        preg_match("|<samlp:SessionIndex>(.*)</samlp:SessionIndex>|", $_POST['logoutRequest'], $tick, PREG_OFFSET_CAPTURE, 3);
        $wrappedSamlSessionIndex = preg_replace('|<samlp:SessionIndex>|','',$tick[0][0]);
        $ticket2logout = preg_replace('|</samlp:SessionIndex>|','',$wrappedSamlSessionIndex);
        phpCAS::log("Ticket to logout: ".$ticket2logout);
        //$session_id = preg_replace('/[^\w]/','',$ticket2logout);
        $cache = cache::make('auth_casw', 'ticket');
        $session_id = $cache->get($ticket2logout);
        phpCAS::log("Session id: ".$session_id);
        if(!$session_id)
        {
            phpCAS::log("No Session id for ticket: ".$ticket2logout);
            exit;
        }

        // destroy a possible application session created before phpcas
        if(session_id()  !== ""){
            session_unset();
            session_destroy();
        }
        // fix session ID
        session_id($session_id);
        $_COOKIE[session_name()]=$session_id;
        $_GET[session_name()]=$session_id;

        // Overwrite session
        session_start();
        $_SESSION = array();
        session_unset();
        session_destroy();
        printf("Disconnected!");
        //file_put_contents('c:\\2.txt','-'.$session_id.'-'.$ticket2logout,FILE_APPEND);
        $cache->delete($ticket2logout);
        phpCAS::traceExit();
        exit();
    }

    /**
    * @inheritdoc
    *
    */
    public function prelogout_hook() {
        global $CFG;

        if (!empty($this->config->logoutcas)) {
            $backurl = $CFG->wwwroot;
            $this->connectCAS();
            phpCAS::logoutWithRedirectService($backurl);
        }
    }
}
