<?php
error_reporting(-1);
date_default_timezone_set("Europe/Berlin");

use \Gepard\Client;
use \Gepard\Event;
include "vendor/autoload.php";

$cl = new Client();


$res = $cl->request("getFileList");
var_dump($res->getBody());

//while(true) {
  //$response = $cl->listen($eventname, true);
  //echo $response;
  //sleep(1);
//}
