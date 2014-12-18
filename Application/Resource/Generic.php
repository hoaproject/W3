<?php

namespace Application\Resource;

use Hoa\Promise;
use Application\Dispatcher\Kit;

class Generic extends Resource {

    protected $_metaData = [
        'home'        => ['translation' => 'Index'],
        'source'      => ['translation' => 'Source'],
        'literature'  => ['translation' => 'Literature'],
        'events'      => ['translation' => 'Event'],
        'event'       => ['translation' => 'Event'],
        'community'   => ['translation' => 'Community'],
        'about'       => ['translation' => 'About'],
        'foundation'  => ['translation' => 'Foundation'],
        'foundation+' => ['translation' => 'Foundation']
    ];



    public function get ( Kit $_this ) {

        $metaData = $this->_metaData;
        $theRule  = $_this->router->getTheRule();
        $file     = $theRule[$_this->router::RULE_VARIABLES]['_uri'];

        if('.html' === substr($file, -5, 5))
            $file = substr($file, 0, strlen($file) - 5);

        $_this
            ->promise
            ->then(function ( Kit $kit ) use ( &$file, $theRule ) {

                if('home' === $theRule[$kit->router::RULE_ID])
                    $file = 'Welcome';

                return $kit;
            })
            ->then(function ( Kit $kit ) use ( $metaData, $theRule ) {

                $ruleId = $theRule[$kit->router::RULE_ID];

                if(!isset($metaData[$ruleId]))
                    return $kit;

                $translationId = $metaData[$ruleId]['translation'];

                $subPromise = new Promise(function ( $fulfill ) use ( $kit ) {

                    $fulfill($kit);
                });
                $subPromise
                    ->then(curry([$this, 'doTranslation'], …, $translationId, $translationId))
                    ->then(function ( Kit $kit ) use ( $translationId ) {

                        return $this->doTitle(
                            $kit,
                            $kit->view->getTranslation($translationId)
                                      ->_($translationId)
                        );
                    });

                return $subPromise;
            })
            ->then(curry([$this, 'doMainOverlay'], …, $file))
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);
    }
}
