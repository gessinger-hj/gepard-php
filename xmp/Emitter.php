#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard;

$name = "ALARM";
if ($argc > 1) {
	$name = $argv[1];
}

// Now send Event

Client::getInstance()->emit($name);
// or

$client = Client::getInstance();

// or
$client->emit($name);

// or
$client->emit($name,["A" => "B"]);

// or
$e = new Event($name);
$e->setValue( "NAME", 4711 );
$client->emit($e);

