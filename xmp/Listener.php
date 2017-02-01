#!/usr/bin/env php
<?php

namespace Gepard;

require ( __DIR__ . '/../vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();

$cl->on ( 'shutdown', function($e) {
	echo ( $e ) ;
}) ;

$eventNameList = ["ALARM","BLARM"] ;
$cl->on($eventNameList);
while ( true ) {
  $ev = $cl->listen ( $eventNameList ) ;
  if ( $ev->getType() === "shutdown" ) {
  	print ( "shutdown as requested.\n" ) ;
    break ;
  }
  echo ( $ev ) ;
}
