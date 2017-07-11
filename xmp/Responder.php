#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();
$eventNameList = ["getFileList"] ;

$cl->on($eventNameList);
$cl->on("shutdown");

$fileList = [ "a.php", "b.php", "c.php" ] ;

while ( true ) {
  $ev = $cl->listen ( $eventNameList ) ;
  echo ( "Request in\n" ) ;
  echo ( "File list out:\n" ) ;
  var_dump ( $fileList ) ;
  echo ( "\n" ) ;
  $ev->setValue ( "file_list", $fileList ) ;
  $ev->setStatus ( 0, "success", "file-list collected" ) ;
  $ev->sendBack() ;
}
