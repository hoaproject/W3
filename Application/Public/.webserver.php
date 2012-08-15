<?php

require_once dirname(dirname(__DIR__)) .
             DIRECTORY_SEPARATOR . 'Data' .
             DIRECTORY_SEPARATOR . 'Core.link.php';

from('Hoa')
-> import('Router.Http')
-> import('Dispatcher.Basic')
-> import('File.Read')
-> import('Mime.~');

$router = new Hoa\Router\Http();
$router
    ->get('i', '(|.*\.html)', function ( ) {

        require 'index.php';
    })
    ->get('a', '.*', function ( Hoa\Dispatcher\Kit $_this ) {

        $file = __DIR__ . DS . $_this->router->getURI();

        if(true === file_exists($file)) {

            $stream = new Hoa\File\Read($file);
            $mime   = new Hoa\Mime($stream);

            header('Content-Type: ' . $mime->getMime());
            echo $stream->readAll();
        }
        else {

            header('Status: 500 Internal Server Error');
            echo '500';
        }
    });

$dispatcher = new Hoa\Dispatcher\Basic();
$dispatcher->dispatch($router);
