<?php

namespace {

from('Hoa')
-> import('Dispatcher.Kit')
-> import('File.Read')
-> import('Http.Response.~')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('Translate.Gettext');

from('Application')
-> import('Model.Visitor')
-> import('Controller.Exception');

}

namespace Application\Controller {

class Generic extends \Hoa\Dispatcher\Kit {

    protected static $_visitor   = null;
    protected static $_languages = array(
        'en' => array(
            'name'    => 'english',
            'regions' => array('en_GB.UTF-8', 'en_GB.utf8', 'en_GB')
        ),
        'fr' => array(
            'name'    => 'français',
            'regions' => array('fr_FR.UTF-8', 'fr_FR.utf8', 'fr_FR')
        )
    );



    public function construct ( ) {

        if(false === $this->router->isAsynchronous())
            $main = 'hoa://Application/View/Shared/Main.xyl';
        else
            $main = 'hoa://Application/View/Shared/Main.fragment.xyl';

        $xyl = new \Hoa\Xyl(
            new \Hoa\File\Read($main),
            new \Hoa\Http\Response(),
            new \Hoa\Xyl\Interpreter\Html(),
            $this->router
        );
        $xyl->setTheme('');

        $this->view = $xyl;
        $this->data = $xyl->getData();

        $this->computeLanguage(static::getVisitor()->getLanguage());
        $this->addTranslation('Main', '__main__');

        $theRule   = $this->router->getTheRule();
        $variables = $theRule[\Hoa\Router\Http::RULE_VARIABLES];
        $footer    = array();

        foreach($variables as &$variable)
            if(is_string($variable))
                $variable = ucfirst($variable);

        foreach(static::$_languages as $language => $details) {

            $variables['language'] = ucfirst($language);
            $footer[] = array(
                'language' => $language,
                'name'     => $details['name'],
                'link'     => $this->router->unroute(
                    $theRule[\Hoa\Router\Http::RULE_ID],
                    $variables
                )
            );
        }

        $this->data->footer = $footer;

        return;
    }

    public function render ( ) {

        if(false === $this->router->isAsynchronous()) {

            $this->view->render();

            return;
        }

        $this->view->interprete();
        $this->view->render($this->view->getSnippet('async_content'));

        return;
    }

    public static function getVisitor ( ) {

        if(null === static::$_visitor)
            static::$_visitor = new \Application\Model\Visitor();

        return static::$_visitor;
    }

    public static function isLanguageAllowed ( $language ) {

        return true === array_key_exists($language, static::$_languages);
    }

    protected function computeLanguage ( $language, $translation = null ) {

        if(false === static::isLanguageAllowed($language)) {

            $view = new \Hoa\Xyl(
                new \Hoa\File\Read('hoa://Application/View/Shared/ChooseLanguage.xyl'),
                $this->view->getOutputStream(),
                new \Hoa\Xyl\Interpreter\Html(),
                $this->router
            );
            $this->view = $view;
            $this->data = $view->getData();

            $this->addTranslation('Main', '__main__', 'en');
            $this->view->getOutputStream()->sendStatus(
                \Hoa\Http\Response::STATUS_NOT_FOUND
            );
            $this->data->title = 'Choose your language';
            $this->render();

            exit; // yup, it sucks.
        }

        setlocale(LC_ALL, static::$_languages[$language]['regions']);

        if(null !== $translation)
            $this->addTranslation($translation);

        return ucfirst($language);
    }

    public function addTranslation ( $filename, $id = null, $language = null ) {

        $language = ucfirst($language ?: static::getVisitor()->getLanguage());
        $this->view->addTranslation(
            new \Hoa\Translate\Gettext(
                new \Hoa\File\Read(
                    'hoa://Data/Etc/Locale/' . $language . '/' . $filename .  '.mo'
                )
            ),
            $id ?: $filename
        );

        return $this->getTranslation($filename);
    }

    public function getTranslation ( $id = '__main__' ) {

        return $this->view->getTranslation($id);
    }
}

}
