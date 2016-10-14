#!/usr/bin/env php
<?php

namespace Gepard;

require ( __DIR__ . '/../vendor/autoload.php' );

use Gepard\Event;
use Gepard\Client;

$cl = new Client();
// $e = new Event("ALARM");
$eventNameList = ["ALARM","BLARM"] ;
$cl->on(["ALARM","BLARM"]);
while ( true ) {
  $ev = $cl->listen ( $eventNameList ) ;
  echo ( $ev ) ;
}
// sleep ( 32000 ) ;