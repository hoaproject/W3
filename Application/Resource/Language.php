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

        $_this->promise = $_this->promise->then(
            curry([$this, 'doTranslation'], …, 'Main', '__main__')
        );

        $_this->router->removeRule('choose-language');
        $_this->router
            ->get(
                'source',
                '/Source\.html',
                'Generic'
            )
            ->get(
                'literature',
                '/Literature\.html',
                'Generic'
            )
            ->get(
                'learn',
                '/Literature/Learn/(?<chapter>\w+)\.html',
                'Literature\Learn'
            )
            ->get(
                'hack',
                '/Literature/Hack/(?<chapter>[\w ]+)\.html',
                'Literature\Hack'
            )
            ->get(
                'events',
                '/Event\.html',
                'Generic'
            )
            ->get(
                'event',
                '/Event/(?<event>\w+)\.html',
                'Generic'
            )
            ->get(
                'community',
                '/Community\.html',
                'Generic'
            )
            ->get(
                'about',
                '/About\.html',
                'Generic'
            )
            ->head_get(
                'home',
                '/',
                'Generic'
            )


                ->_get(
                    'lt',
                    '/Literature/Mini-tutorial\.html'
                )
                ->_get(
                    'lr',
                    '/Literature/Research/(?<article>[\w\d]+)\.html'
                )
                ->_get(
                    'lc',
                    '/Literature/Contributor/Guide\.html'
                )
                ->_get(
                    'r',
                    '/Research\.html'
                )
                ->get_post(
                    'rx',
                    '/Research/(?<article>\w+)/Experimentation\.html'
                )
                ->_get(
                    'v-',
                    '/Awecode\.html'
                )
                ->_get(
                    'v',
                    '/Video\.html'
                )
                ->_get(
                    'v+',
                    '/Awecode/(?<id>[\w\-_]+)\.html'
                )
                ->_get(
                    'contact',
                    '/Contact\.html'
                )
                ->_get(
                    'f',
                    '/Foundation.html'
                )
                ->_get(
                    'f+',
                    '/Foundation/(?<_able>\w+)\.html'
                )
                ->_get(
                    'u',
                    '/Whouse/(?<who>\w+)\.html'
                )
                ->_get(
                    'e',
                    '/Error\.html'
                );

        if(empty($uri))
            $uri = '/';

        $_this->router->route($uri);
        $_this->router->setPrefix('/' . ucfirst($language));

        $theRule = &$_this->router->getTheRule();
        $theRule[Router::RULE_VARIABLES]['_this'] = $_this;

        $_this->dispatcher->dispatch($_this->router);

        return;
    }
}
