<?php

require_once 'vendor/autoload.php';

$autoloader = new Hoa\Consistency\Autoloader();
$autoloader->addNamespace('Hoa\Model', '/usr/local/lib/Hoa/Model/');
$autoloader->register();
