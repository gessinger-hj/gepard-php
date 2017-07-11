#!/usr/bin/env php
<?php
namespace Gepard;

error_reporting ( E_ALL ) ;

require ( 'vendor/autoload.php' );

use Gepard\Client;

$cl = new Client();
$e = $cl->request ( "getFileList" ) ;
var_dump ( $e->getValue ( "file_list" ) ) ;
