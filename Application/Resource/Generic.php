<?php

namespace Application\Resource;

use Application\Dispatcher\Kit;
use Hoa\Promise;

class Generic extends Resource
{
    protected $_metaData = [
        'source'      => ['translation' => 'Source'],
        'literature'  => ['translation' => 'Literature'],
        'events'      => ['translation' => 'Event'],
        'event'       => ['translation' => 'Event'],
        'community'   => ['translation' => 'Community'],
        'about'       => ['translation' => 'About'],
        'foundation'  => ['translation' => 'Foundation'],
        'foundation+' => ['translation' => 'Foundation']
    ];



    public function get(Kit $_this)
    {
        $metaData = $this->_metaData;
        $theRule  = $_this->router->getTheRule();
        $router   = $_this->router;
        $file     = $theRule[$router::RULE_VARIABLES]['_uri'];

        if ('.html' === substr($file, -5, 5)) {
            $file = substr($file, 0, strlen($file) - 5);
        }

        $_this
            ->promise
            ->then(function (Kit $kit) use (&$file, $theRule) {

                $router = $kit->router;

                if ('home' === $theRule[$router::RULE_ID]) {
                    $file = 'Welcome';
                }

                return $kit;
            })
            ->then(function (Kit $kit) use ($metaData, $theRule) {

                $router = $kit->router;
                $ruleId = $theRule[$router::RULE_ID];

                if (!isset($metaData[$ruleId])) {
                    return $kit;
                }

                $translationId = $metaData[$ruleId]['translation'];

                $subPromise = new Promise(function ($fulfill) use ($kit) {

                    $fulfill($kit);
                });
                $subPromise
                    ->then(curry([$this, 'doTranslation'], …, $translationId, $translationId))
                    ->then(function (Kit $kit) use ($translationId) {

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
