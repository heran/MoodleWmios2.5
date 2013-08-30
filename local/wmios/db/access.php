<?php

$capabilities = array(
    'local/wmios:notes_update_note' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
             'guest' => CAP_PREVENT,
             'student' => CAP_ALLOW,
             'teacher' => CAP_ALLOW,
             'editingteacher' => CAP_ALLOW,
             'coursecreator' => CAP_ALLOW,
             'manager' => CAP_ALLOW
        ),
        'riskbitmask' => RISK_DATALOSS,
    ),
    'local/wmios:notes_list_note' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
             'guest' => CAP_PREVENT,
             'student' => CAP_ALLOW,
             'teacher' => CAP_ALLOW,
             'editingteacher' => CAP_ALLOW,
             'coursecreator' => CAP_ALLOW,
             'manager' => CAP_ALLOW
        ),
        'riskbitmask' => RISK_PERSONAL,
    ),
    'local/wmios:qanda_update_question' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
             'guest' => CAP_PREVENT,
             'student' => CAP_ALLOW,
             'teacher' => CAP_ALLOW,
             'editingteacher' => CAP_ALLOW,
             'coursecreator' => CAP_ALLOW,
             'manager' => CAP_ALLOW
        ),
        'riskbitmask' => RISK_DATALOSS,
    ),
    'local/wmios:qanda_update_answer' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
             'guest' => CAP_PREVENT,
             'student' => CAP_ALLOW,
             'teacher' => CAP_ALLOW,
             'editingteacher' => CAP_ALLOW,
             'coursecreator' => CAP_ALLOW,
             'manager' => CAP_ALLOW
        ),
        'riskbitmask' => RISK_DATALOSS,
    ),
    'local/wmios:qanda_list_qanda' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
             'guest' => CAP_PREVENT,
             'student' => CAP_ALLOW,
             'teacher' => CAP_ALLOW,
             'editingteacher' => CAP_ALLOW,
             'coursecreator' => CAP_ALLOW,
             'manager' => CAP_ALLOW
        ),
        'riskbitmask' => RISK_PERSONAL,
    ),
    'local/wmios:org_cohort_manage' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
             'guest' => CAP_PREVENT,
             'student' => CAP_PREVENT,
             'teacher' => CAP_PREVENT,
             'editingteacher' => CAP_PREVENT,
             'coursecreator' => CAP_PREVENT,
             'manager' => CAP_ALLOW
        ),
        'riskbitmask' => RISK_PERSONAL,
    )
);