#!/usr/bin/env php
<?php

namespace Gepard;

require ( 'vendor/autoload.php' );

use Gepard\Event;
use Gepard\Client;

$cl = new Client();
$e = new Event("ALARM");
$cl->emit($e);