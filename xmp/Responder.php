#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();

function getFileList ($e)
{
	$fileList = [ "a.php", "b.php", "c.php" ] ;
  echo ( "Request in: getFileList\n" ) ;
  echo ( "File list out:" . implode(',', $fileList) . "\n" ) ;
  $e->setValue ( "file_list", $fileList ) ;
  $e->setStatus ( 0, "success", "file-list collected" ) ;
  $e->sendBack() ;
}

$cl->on("getFileList",function($e) {
	getFileList($e);
});

$cl->on ( 'shutdown', function($e) {
	echo ( $e ) ;
}) ;

$cl->listen() ;
