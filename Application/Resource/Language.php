<?php

namespace Application\Resource;

use Hoa\Locale;
use Hoa\Router;
use Application\Dispatcher\Kit;

class Language extends Resource {

    public function get ( Kit $_this, $language, $uri ) {

        $language = strtolower($language);
        $_this->user->guessLanguage($language);
        $finalLanguage = $_this->user->getLocale()->getLanguage();

        if($language !== $finalLanguage) {

            $response = $_this->view->getOutputStream();
            $response->sendStatus($response::STATUS_NOT_FOUND);
            $response->sendHeader(
                'Location',
                $_this->router->unroute(
                    'choose-language',
                    [
                        'language' => ucfirst($finalLanguage),
                        'uri'      => $uri
                    ]
                )
            );

            return;
        }

        $_this->data->language[0] = $language;

        $_this->promise = $_this->promise->then(
            curry([$this, 'doTranslation'], …, 'Main', '__main__')
        );

        $_this->router->removeRule('choose-language');
        $_this->router->removeRule('fallback');
        $router = $_this->router;

        require 'hoa://Application/Router.php';

        if(empty($uri))
            $uri = '/';

        try {

            $_this->router->route($uri);
        }
        catch ( Router\Exception\NotFound $e ) {

            $router->route('/Error.html');
            $rule                                       = &$router->getTheRule();
            $rule[$router::RULE_VARIABLES]['exception'] = $e;
            $_this->dispatcher->dispatch($router);

            return;
        }

        $_this->router->setPrefix('/' . ucfirst($language));

        $theRule = &$_this->router->getTheRule();
        $theRule[Router::RULE_VARIABLES]['_this'] = $_this;

        $_this->promise = $_this->promise->then(
            curry([$this, 'doComment'], …)
        );

        $_this->dispatcher->dispatch($_this->router);

        return;
    }
}
