<?php

namespace Application\Controller {

class Video extends \Hoa\Dispatcher\Kit {

    public function DefaultAction ( ) {

        $this->view->addOverlay('hoa://Application/View/Video/Video.xyl');
        $this->view->render();

        return;
    }

    public function PraspelAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Video/Praspel.xyl');
        $this->view->render();

        return;
    }

    public function PopcodeAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Video/Popcode.xyl');
        $this->view->render();

        return;
    }
}

}
