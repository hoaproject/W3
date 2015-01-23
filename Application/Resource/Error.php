<?php

namespace Application\Resource;

use Hoa\Router;
use Hoa\Http;
use Application\Dispatcher\Kit;

class Error extends Resource {

    public function get ( Kit $_this, $exception ) {

        switch(get_class($exception)) {

            case 'Hoa\Router\Exception\NotFound':
                $_this->view->getOutputStream()->sendStatus(
                    Http\Response::STATUS_NOT_FOUND
                );
              break;

            default:
                $_this->view->getOutputStream()->sendStatus(
                    Http\Response::STATUS_INTERNAL_SERVER_ERROR
                );
        }

        $_this
            ->promise
            ->then(curry([$this, 'doTranslation'], …, 'Error', 'Error'))
            ->then(curry([$this, 'doTitle'], …, 'Error'))
            ->then(curry([$this, 'doMainOverlay'], …, 'Error'))
            ->then([$this, 'doComment'])
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender'])
            ;

        return;
    }
}
