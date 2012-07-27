<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Index extends Generic {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Welcome.xyl');
        $this->render();

        return;
    }

    public function ContactAction ( ) {

        $this->view->addOverlay('hoa://Application/View/Contact.xyl');
        $this->render();

        return;
    }
}

}
