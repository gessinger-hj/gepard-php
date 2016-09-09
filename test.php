<?php
use \Gepard\Client;

include "vendor/autoload.php";

$cl = new \Gepard\Client();

while(true) {
  $eventname = "Alarm";
  echo PHP_EOL."listening for ".$eventname.PHP_EOL;
  $response = $cl->listen($eventname, true);
  echo $response;
  sleep(1);
}
