<?php

namespace {

from('Hoa')
-> import('Dispatcher.Basic.Controller')
-> import('File.Read')
-> import('Http.Response.~')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~');

}

namespace Application\Controller {

class Generic extends \Hoa\Dispatcher\Basic\Controller {

    public function construct ( ) {

        if(false === $this->router->isAsynchronous())
            $main = 'hoa://Application/View/Main.xyl';
        else
            $main = 'hoa://Application/View/Main.fragment.xyl';

        $xyl = new \Hoa\Xyl(
            new \Hoa\File\Read($main),
            new \Hoa\Http\Response(),
            new \Hoa\Xyl\Interpreter\Html(),
            $this->router
        );
        $xyl->setTheme('');

        $this->view = $xyl;
        $this->data = $xyl->getData();

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
}

}
