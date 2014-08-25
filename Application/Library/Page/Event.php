<?php

namespace Application\Library\Page {

class Event extends Base {

    const PATTERN_URL  = 'http://hoa-project.net/[A-Za-z]+/Event/([a-zA-Z0-9-]+).html';

    const PAGE_TYPE = 'event';

    protected function initData( $url ) {

        preg_match('#'.self::PATTERN_URL.'#', $url, $matches);
        $event = $matches[1];

        $this->data['type']   = 'event';
        $this->data['event']  = $event;

        parent::initData($url);

    }

    protected function getAnchor () {
        return $this->data['event'] . ' (' . $this->getHtmlNode('h2') . ')';
    }
}

}