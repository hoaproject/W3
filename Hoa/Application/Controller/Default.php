<?php

class DefaultController extends \Hoa\Controller\Application {

    public function DefaultAction ( )  {

        $this->view->addOverlay('hoa://Application/View/Welcome.xyl');
        $this->view->render();

        return;
    }
}
