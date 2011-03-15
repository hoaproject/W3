<?php

class DefaultController {

    public function DefaultAction ( \Hoa\Controller\Application $_this )  {

        $_this->view->addUse('hoa://Application/View/Welcome.xyl');
        $_this->view->render();

        return;
    }
}
