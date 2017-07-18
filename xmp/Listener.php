#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\Client;

$client = new Client();

$client->on ( 'shutdown', function($e) {
	echo ( $e ) ;
}) ;

$eventNameList = ["ALARM","BLARM"] ;

if ($argc > 1) {
	$name = $argv[1];
}

echo ( "Listen for events with name=" . implode ( ',', $eventNameList ) . "\n" ) ;

$client->on($eventNameList, function($e) {
	echo ( "e->getName()=" . $e->getName() . "\n" ) ;
	echo("============== body ===============\n");
	var_dump($e->getBody());
});

$client->listen() ;
