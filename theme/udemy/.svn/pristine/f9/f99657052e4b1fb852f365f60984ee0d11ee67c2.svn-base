<?php

/*
* Include renderers
*/

$directory = new DirectoryIterator(dirname(__FILE__).'/renderers');
/** @var DirectoryIterator $file*/
foreach($directory as $file){
    if(!$file->isFile())continue;
    include_once($file->getPath().'/'.$file->getFilename());
}