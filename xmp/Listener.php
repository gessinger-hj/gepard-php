#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();

$cl->on ( 'shutdown', function($e) {
	echo ( $e ) ;
}) ;

$eventNameList = ["ALARM","BLARM"] ;
echo ( "Listen for events with name=" . implode ( ',', $eventNameList ) . "\n" ) ;

$cl->on($eventNameList, function($e) {
	echo($e);
});

$cl->listen() ;
