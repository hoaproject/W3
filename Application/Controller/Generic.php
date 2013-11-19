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

    protected static $_visitor = null;



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

    protected function computeLanguage ( $language, $translation = null ) {

        if(   'en' !== $language
           && 'fr' !== $language) {

            $this->addTranslation('Error', null, 'en');
            $tr = $this->getTranslation('Error');

            $this->view->getOutputStream()->sendStatus(
                \Hoa\Http\Response::STATUS_NOT_FOUND
            );

            $this->data->title = $tr->_('Error');
            $this->view->addOverlay('hoa://Application/View/Shared/Error.xyl');
            $this->render();

            return;
        }

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
