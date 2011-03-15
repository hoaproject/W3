<?php

class VideoController extends \Hoa\Controller\Application {

    public function PraspelAction ( )  {

        $this->view->addUse('hoa://Application/View/Video/Praspel.xyl');
        $this->view->render();

        return;
    }
}
