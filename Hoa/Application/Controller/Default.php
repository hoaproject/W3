<?php

class DefaultController extends \Hoa\Controller\Application {

    public function DefaultAction ( )  {

        $this->view->addUse('hoa://Application/View/Welcome.xyl');
        $this->view->render();

        return;
    }
}
