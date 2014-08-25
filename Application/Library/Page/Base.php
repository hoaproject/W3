<?php

namespace Application\Library\Page {

class Base implements PageInterface {

    protected $data = array();

    protected $crawler;

    const PAGE_TYPE = 'base';

    public function __construct( $url, \Symfony\Component\DomCrawler\Crawler $crawler ) {

        $this->crawler = $crawler;

        $this->initData( $url );
    }

    protected function initData( $url ) {

        $this->data['url']     = $url;
        $this->data['type']     = static::PAGE_TYPE;
        $this->data['content']  = $this->getHtmlNode('body');
        $this->data['title']    = $this->getHtmlNode('title');
        $this->data['subtitle'] = $this->getHtmlNode('h1');
        $this->data['anchor'] = $this->getAnchor();
    }

    public function getData( $name = null ) {

        if($name === null) {
            return $this->data;
        }

        if(array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    protected function getHtmlNode( $nodeName ) {

        return $this->crawler->filter($nodeName)->count() > 0 ? $this->crawler->filter($nodeName)->html() : '';
    }

    protected function getAnchor( ) {

        return $this->data['title'];
    }

    protected function getAdditionalLinks( ) {

        return [];
    }

}

}