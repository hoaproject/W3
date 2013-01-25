<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Event extends Generic {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Event/Event.xyl');
        $this->render();

        return;
    }
}

}
