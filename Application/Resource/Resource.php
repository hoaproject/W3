<?php

namespace Application\Resource;

use Application;
use Application\Dispatcher\Kit;
use Hoa\Core;
use Hoa\File;
use Hoa\Http;
use Hoa\Promise;
use Hoa\Router;
use Hoa\Translate;
use Hoa\Xyl;

class Resource {

    public function doTranslation ( Kit $kit, $translationFile, $translationId = null ) {

        $language = $kit->user->getLocale()->getLanguage();

        $kit->view->addTranslation(
            new Translate\Gettext(
                new File\Read(
                    'hoa://Data/Etc/Locale/' .
                    ucfirst($language) .
                    '/' .
                    $translationFile .
                    '.mo'
                )
            ),
            $translationId ?: '__main__'
        );

        return $kit;
    }

    public function doTitle ( Kit $kit, $title ) {

        $kit->data->title = $title;

        return $kit;
    }

    public function doMainOverlay ( Kit $kit, $file ) {

        $_file = 'hoa://Application/View/' .
                 ucfirst($kit->user->getLocale()->getLanguage()) .
                 '/' . $file . '.xyl';

        if(false === file_exists($_file))
            $_file = 'hoa://Application/View/Shared/' . $file . '.xyl';

        $kit->view->addOverlay($_file);

        return $kit;
    }

    public function doFooter ( Kit $kit ) {

        $footer  = [];
        $theRule = $kit->router->getTheRule();
        $variables = $theRule[$kit->router::RULE_VARIABLES];

        foreach($variables as &$variable)
            if(is_string($variable))
                $variable = ucfirst($variable);

        $oldPrefix = $kit->router->getPrefix();

        foreach($kit->user->getAvailableLanguages() as $short => $langName) {

            $kit->router->setPrefix('/' . ucfirst($short));
            $footer[] = [
                'link'     => $kit->router->unroute(
                    $theRule[$kit->router::RULE_ID],
                    $variables
                ),
                'name'     => $langName,
                'language' => $short,
                'Language' => ucfirst($short)
            ];
        }

        $kit->router->setPrefix($oldPrefix);
        $kit->data->footer = $footer;

        return $kit;
    }

    public function doRender ( Kit $kit ) {

        if(false === $kit->router->isAsynchronous()) {

            try {

                $kit->view->render();
            }
            catch ( \Exception $e ) {

                var_dump($e->getMessage());
            }

            return;
        }

        $kit->view->interprete();
        $kit->view->render($kit->view->getSnippet('async_content'));

        return;
    }
}

Core\Consistency::flexEntity('Application\Resource\Resource');
