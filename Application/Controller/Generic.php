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
-> import('Model.Visitor');

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

        $this->addTranslation('Main');

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

    public function addTranslation ( $filename ) {

        $language = ucfirst(static::getVisitor()->getLanguage());

        return $this->view->addTranslation(new \Hoa\Translate\Gettext(
            new \Hoa\File\Read(
                'hoa://Data/Etc/Locale/' . $language . '/' . $filename .  '.mo'
            )
        ));
    }
}

}
