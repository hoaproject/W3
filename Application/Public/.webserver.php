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
    ->all('a', '.*', function ( Hoa\Dispatcher\Kit $_this ) {

        $uri  = $_this->router->getURI();
        $file = __DIR__ . DS . $uri;

        if(!empty($uri) && true === file_exists($file)) {

            $stream = new Hoa\File\Read($file);
            $mime   = new Hoa\Mime($stream);

            header('Content-Type: ' . $mime->getMime());
            echo $stream->readAll();

            return;
        }

        require 'index.php';
    });

$dispatcher = new Hoa\Dispatcher\Basic();
$dispatcher->dispatch($router);
