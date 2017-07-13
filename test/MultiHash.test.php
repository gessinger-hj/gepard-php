#!/usr/bin/env php
<?php
namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\MultiHash ;

$mh = new MultiHash() ;
$mh->put ( "A", "A-VALUE-1" ) ;
$mh->put ( "A", "A-VALUE-2" ) ;
$mh->put ( "B", "B-VALUE" ) ;
echo ( $mh ) ;
$f = $mh->get("B") ;
var_dump($f);