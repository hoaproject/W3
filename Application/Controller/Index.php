<?php

namespace Application\Controller {

class Index extends \Hoa\Dispatcher\Kit {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Welcome.xyl');
        $this->view->render();

        return;
    }

    public function ContactAction ( ) {

        $this->view->addOverlay('hoa://Application/View/Contact.xyl');
        $this->view->render();

        return;
    }
}

}
