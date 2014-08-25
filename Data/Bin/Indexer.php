<?php

require_once dirname(dirname(__DIR__)) .
    DIRECTORY_SEPARATOR . 'Data' .
    DIRECTORY_SEPARATOR . 'Core.link.php';

require_once dirname(dirname(__DIR__)) .
    DIRECTORY_SEPARATOR . 'vendor' .
    DIRECTORY_SEPARATOR . 'autoload.php';

\Hoa\Core::enableErrorHandler();
\Hoa\Core::enableExceptionHandler();

from('Hoa')
-> import('Console.Cursor')
;

from('Application')
-> import('Library.ElasticSearch')
-> import('Library.Crawler.Hoa')
-> import('Library.Page.*')
;

\Hoa\Console\Cursor::colorize('fg(green) bg(black)');

$elasticSearch = new \Application\Library\ElasticSearch();

$langs = Application\Library\Crawler\Hoa::$_languages;
foreach($langs as $uri => $lang) {

    $HoaCrawler  = new \Application\Library\Crawler\Hoa('http://hoa-project.net/'.$uri.'/');
    while($HoaCrawler->hasLinkToCrawl()) {

        if(!$crawler = $HoaCrawler->getNextPage()) {
            continue;
        }

        $page = \Application\Library\Page\Factory::create(
            $HoaCrawler->getCurrentLink(),
            $crawler
        );

        $id = md5($page->getData('url'));
        $document = $page->getData();
        $document['lang'] = $lang;

        echo "Index the page " . $page->getData('url')."\n";

        $elasticSearch->addPage($document, $id);
    }
}



