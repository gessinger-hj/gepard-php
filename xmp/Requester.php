#!/usr/bin/env php
<?php

namespace Gepard;

require ( __DIR__ . '/../vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();
$result = $cl->request ( "getFileList" ) ;
$body = $result->getBody() ;
var_dump ( $body ) ;
var_dump ( $result->getValue ( "file_list" ) ) ;