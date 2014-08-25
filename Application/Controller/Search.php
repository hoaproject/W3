<?php

namespace {

    from('Application')
        -> import('Controller.Generic')
        -> import('Library.Crawler.Hoa');

}

namespace Application\Controller {

class Search extends Generic {


    public function DefaultAction ( $language )  {

        $language = $this->computeLanguage($language, 'Search');
        $tr = $this->getTranslation('Search');

        $this->data->title = $tr->_('Search: documentation, manual, tutorial…');
        $this->view->addOverlay('hoa://Application/View/' . $language . '/Search.xyl');

        $this->view->interprete();
        $data = $this->view->getData();
        $form = $this->view->getElement('form_search');

        if(true === $form->hasBeenSent() && true === $form->isValid()) {
            $data->results = $this->search($language, $form->getData('query'));
        }

        $this->render();

        return;
    }

    private function search( $language, $query )  {

        $query = array(
            'bool' => array(
                'must' => array(
                    array('query_string' => array('query' => $query)),
                    array('term' => array('lang' => \Application\Library\Crawler\Hoa::$_languages[$language]))
                )
            )
        );

        return (new \Application\Library\ElasticSearch())->search($query);
    }
}

}
