<?php

namespace {

from('Application')
-> import('Controller.Generic')

}

namespace Application\Controller {

class Awecode extends Generic {

    public function RedirectAction ( ) {

        $this->view->getOutputStream()->sendHeader(
            'Location',
            $this->router->unroute('v'),
            true,
            \Hoa\Http\Response::STATUS_MOVED_PERMANENTLY
        );
    }
}

}
