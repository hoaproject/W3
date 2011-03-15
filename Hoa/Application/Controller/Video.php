<?php

class VideoController {

    public function PraspelAction ( \Hoa\Controller\Application $_this )  {

        $_this->view->addUse('hoa://Application/View/Video/Praspel.xyl');
        $_this->view->render();

        return;
    }
}
