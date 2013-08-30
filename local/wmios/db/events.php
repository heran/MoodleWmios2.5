<?php


/* List of handlers */
$handlers = array (
    'course_deleted' => array (
        'handlerfile'      => '/local/wmios/locallib.php',
        'handlerfunction'  => 'local_wmios_course_deleted',
        'schedule'         => 'instant',
        'internal'         => 1,
    ),
    'mod_deleted' => array (
        'handlerfile'      => '/local/wmios/locallib.php',
        'handlerfunction'  => 'local_wmios_mod_deleted',
        'schedule'         => 'instant',
        'internal'         => 1,
    ),
    'user_deleted' => array (
        'handlerfile'      => '/local/wmios/locallib.php',
        'handlerfunction'  => 'local_wmios_user_deleted',
        'schedule'         => 'instant',
        'internal'         => 1,
    )
    
);