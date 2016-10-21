#!/usr/bin/env php
<?php

namespace Gepard;

require ( __DIR__ . '/../vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();
$eventNameList = ["ALARM","BLARM"] ;
$cl->on($eventNameList);
while ( true ) {
  $ev = $cl->listen ( $eventNameList ) ;
  echo ( $ev ) ;
}
