<?php

namespace Application\Library\Page {

class Learn extends Base {

    const PATTERN_URL  = 'http://hoa-project.net/[A-Za-z]+/Literature/Learn/([A-Za-z_-]+).html';

    const PAGE_TYPE = 'learn';

    protected function getAnchor( ) {
        return $this->data['title'] . '(' . array_pop(explode('<br>', $this->data['subtitle'])) . ')';
    }

}

}