<?php

namespace Application\Resource;

use Hoa\Router;
use Hoa\Http;
use Application\Dispatcher\Kit;

class Fallback {

    public function get ( Kit $_this ) {

        $_this->router->removeRule('fallback');
        $router = $_this->router;

        require 'hoa://Application/Router.php';

        try {

            $_this->router->route();
        }
        catch ( Router\Exception\NotFound $e ) {

            $router->route('/Error.html');
            $rule                                       = &$router->getTheRule();
            $rule[$router::RULE_VARIABLES]['exception'] = $e;
            $_this->dispatcher->dispatch($router);

            return;
        }

        $userAgent = Http\Runtime::getHeader('User-Agent');
        $response  = $_this->view->getOutputStream();

        if(0 !== preg_match('#^Isso/#', $userAgent)) {

            $response->sendStatus($response::STATUS_OK);

            return;
        }

        $_this->user->guessLanguage(null);
        $language = $_this->user->getLocale()->getLanguage();

        $response->sendStatus($response::STATUS_PERMANENT_REDIRECT);
        $response->sendHeader(
            'Location',
            $_this->router->unroute(
                'choose-language',
                [
                    'language' => ucfirst($language),
                    'uri'      => '/' . $_this->router->getURI()
                ]
            )
        );

        return;
    }
}
