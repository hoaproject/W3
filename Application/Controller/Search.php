<?php

namespace {

    from('Application')
        -> import('Controller.Generic');

}

namespace Application\Controller {

class Search extends Generic {

    const WS_SEARCH = 'http://search.dev.hoa-project.net/%s';

    public function DefaultAction ( $language )  {

        $language = $this->computeLanguage($language, 'Search');
        $tr = $this->getTranslation('Search');

        $this->data->title = $tr->_('Search: documentation, manual, tutorial…');
        $this->view->addOverlay('hoa://Application/View/' . $language . '/Search.xyl');

        $this->view->interprete();
        $data = $this->view->getData();
        $form = $this->view->getElement('form_search');

        if(true === $form->hasBeenSent()) {
            $data->query   = $_POST['query'];
            $data->results = $this->search($language, $_POST['query']);
        }

        $this->render();

        return;
    }

    private function search( $language, $query )  {

        require_once dirname(dirname(__DIR__)) .
            DIRECTORY_SEPARATOR . 'vendor' .
            DIRECTORY_SEPARATOR . 'autoload.php';

        $searchUrl = sprintf(self::WS_SEARCH, $language);

        $client = new \GuzzleHttp\Client();
        $response = $client->post($searchUrl, [
            'body' => [ 'q' => $query ]
        ]);

        return $response->json();
    }
}

}
