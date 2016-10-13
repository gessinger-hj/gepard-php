#!/usr/bin/env php
<?php
namespace Gepard;

require ( __DIR__ . '/../vendor/autoload.php' );

use Gepard\JSAcc ;

$m = [] ;
$a = new JSAcc($m) ;
$a->add ( "M1/M2/N", 11 ) ;
echo ( $a->toJSON ( $a->value ( "M1/M2/N" ) ) ) ; echo ( "\n" ) ;
echo ( $a->toJSON ( $a->value ( "M1/M2" ) ) ) ; echo ( "\n" ) ;
$a->add ( "A/B/C", ["ABCD", "ABCE"] ) ;
$a->add ( "A/B/D", "ABCD" ) ;
$a->add ( "A/AX", "AX" ) ;
$a->add ( "X", "X" ) ;
echo ( $a->toJSON() ) ; echo ( "\n" ) ;
echo ( $a->toJSON ( $a->value ( "A/B" ) ) ) ; echo ( "\n" ) ;
$a->remove ( "X" ) ;
echo ( $a->toJSON() ) ; echo ( "\n" ) ;
$a->remove ( "A/B/D" ) ;
echo ( $a->toJSON() ) ; echo ( "\n" ) ;
$a->remove ( "A" ) ;
echo ( $a->toJSON() ) ; echo ( "\n" ) ;
$a->remove ( "M1/M2/N" ) ;
echo ( $a->toJSON() ) ; echo ( "\n" ) ;
$a->add ( "only/a/map" ) ;
echo ( $a->toJSON() ) ; echo ( "\n" ) ;
var_dump ( $m ) ;
