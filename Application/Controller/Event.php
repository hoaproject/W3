<?php

namespace {

from('Application')
-> import('Controller.Generic');

}

namespace Application\Controller {

class Event extends Generic {

    public function DefaultAction ( $language )  {

        $language = $this->computeLanguage($language, 'Event');
        $tr = $this->getTranslation('Event');

        $this->data->title = $tr->_('Events');
        $this->view->addOverlay('hoa://Application/View/Shared/Event/Event.xyl');
        $this->render();

        return;
    }

    protected function showEvent ( $language, $title, $filename ) {

        $language = $this->computeLanguage($language, 'Event');
        $tr = $this->getTranslation('Event');

        $this->data->title = $tr->_($title);
        $this->view->addOverlay('hoa://Application/View/Shared/Event/' . $filename . '.xyl');
        $this->render();

        return;
    }

    public function Hoaapex14Action ( $language ) {

        return $this->showEvent($language, 'Hoa Apex 2014', 'Hoaapex14');
    }

    public function Phptour14Action ( $language ) {

        return $this->showEvent($language, 'PHPTour 2014', 'Phptour14');
    }

    public function Afuplyon13Action ( $language ) {

        return $this->showEvent($language, 'AFUP Lyon 2013', 'Afuplyon13');
    }

    public function Jdev13Action ( $language ) {

        return $this->showEvent($language, 'JDév\' 2013', 'Jdev13');
    }

    public function Hoaapex13Action ( $language ) {

        return $this->showEvent($language, 'Hoa Apex 2013', 'Hoaapex13');
    }

    public function Cstva13Action ( $language ) {

        return $this->showEvent($language, 'CSTVA 2013', 'Cstva13');
    }

    public function Forumphp12Action ( $language ) {

        return $this->showEvent($language, 'ForumPHP 2012', 'Forumphp12');
    }

    public function Amost12Action ( $language ) {

        return $this->showEvent($language, 'AMOST 2012', 'Amost12');
    }

    public function Phptour11Action ( $language ) {

        return $this->showEvent($language, 'PHPTour 2011', 'Phptour11');
    }
}

}
