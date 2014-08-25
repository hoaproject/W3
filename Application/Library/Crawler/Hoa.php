<?php

namespace Application\Library\Crawler {

class Hoa {

    protected $baseUrl;

    protected $goutte;

    protected $links = array();

    protected $currentLink;

    protected $linksToCrawl = array();

    public static $_languages = array(
        'En' => 'english',
        'Fr' => 'french'
    );

    public function __construct( $baseUrl ) {

        $this->baseUrl = $baseUrl;
        $this->addNewLinks($baseUrl);

        $this->goutte  = new \Goutte\Client();
    }

    public function hasLinkToCrawl( ) {

        return count($this->linksToCrawl) > 0;
    }

    public function getNextPage( ) {

        $this->currentLink = $this->getNextLinkToCrawl();
        if(empty($this->currentLink)) {
            return false;
        }

        $crawler = $this->goutte->request('GET', $this->currentLink);
        if(!$this->isValidPage($this->goutte->getResponse())) {
            return false;
        }

        $this->addNewLinks($this->getLinks($crawler));

        return $crawler;
    }

    private function getNextLinkToCrawl( ) {

        return array_shift($this->linksToCrawl);
    }

    protected function isValidPage( $response ) {

        if($response->getStatus() !== 200) {
            return false;
        }

        return false !== strpos($response->getHeader('Content-type'), 'text/html');
    }

    protected function getLinks( $crawler ) {

        $links = $crawler->filter('a')->each(function ($node) {
            if(false !== strpos($node->link()->getUri(), $this->baseUrl)) {
                return $node->link()->getUri();
            }
        });

        foreach($links as &$link) {

            if($position = strpos($link, '#')) {
                $link = substr($link, 0, $position);
            }
        }

        return $links;
    }

    private function addNewLinks( $links ) {

        $newLinks = array_diff(array_unique((array)$links), $this->links);

        $this->links       += $newLinks;
        $this->linksToCrawl+= $newLinks;
    }

    public function getCurrentLink( ) {

        return $this->currentLink;
    }

}

}
