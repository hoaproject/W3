<?php

namespace Application\Library\Page {

class Factory {

    protected static $pageType = array(
        '\Application\Library\Page\Event'   => \Application\Library\Page\Event::PATTERN_URL,
        '\Application\Library\Page\Library' => \Application\Library\Page\Library::PATTERN_URL,
        '\Application\Library\Page\Learn'   => \Application\Library\Page\Learn::PATTERN_URL,
    );

    public static function create( $url, \Symfony\Component\DomCrawler\Crawler $crawler ) {
        foreach(self::$pageType as $pageType => $pattern) {
            if(preg_match('#'.$pattern.'#', $url)) {
                if (!class_exists($pageType, true) || !in_array('Application\Library\Page\PageInterface', class_implements($pageType, true))) {
                    throw new \Exception('The page type "'.$pageType.'" does not exist', 0);
                }

                return (new \ReflectionClass($pageType))->newInstance($url, $crawler);
            }
        }

        return new Base($url, $crawler);
    }
}

}