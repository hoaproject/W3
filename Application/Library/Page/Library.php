<?php

namespace Application\Library\Page {

class Library extends Base {

    const PATTERN_URL  = 'http://hoa-project.net/[A-Za-z]+/Literature/Hack/([a-zA-Z0-9-]+).html';
    const COMPOSER_URL = 'http://git.hoa-project.net/Library/%s.git/plain/composer.json';
    const README_URL   = 'http://git.hoa-project.net/Library/%s.git/plain/README.md';

    const PAGE_TYPE = 'library';

    protected function initData( $url ) {

        preg_match('#'.self::PATTERN_URL.'#', $url, $matches);
        $library = $matches[1];

        $this->data['composer'] = sprintf(self::COMPOSER_URL, $library);
        $this->data['readme']   = sprintf(self::README_URL, $library);
        $this->data['library']   = $library;

        parent::initData($url);
    }

    protected function getAnchor( ) {

        return 'Hoa\\' . $this->data['library'];
    }

}

}