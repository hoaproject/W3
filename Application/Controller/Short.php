<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Short extends Generic {

    public function DefaultAction ( $short )  {

        $unfold = null;

        switch($short) {

            case 'praspel':
                $unfold = '/Literature/Hack/Praspel.html';
              break;

            default:
                $unfold = '/Error.html';
        }

        $this->view->getOutputStream()->sendHeader(
            'Location',
            $unfold,
            true,
            \Hoa\Http\Response::STATUS_MOVED_PERMANENTLY
        );

        return;
    }
}

}
