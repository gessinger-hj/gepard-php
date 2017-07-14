#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\Event;
use Gepard\Client;

$cl = new Client();
$name = "ALARM";
if ($argc > 1) {
	$name = $argv[1];
}
$e = new Event($name);
$cl->emit($e);