<?php

namespace Application\Resource;

use Application\Dispatcher\Kit;
use Hoa\Promise;
use Hoa\Stringbuffer;
use Hoa\Xml;

class Home extends Resource
{
    public function get(Kit $_this)
    {
        $_this
            ->promise
            ->then(curry([$this, 'doTranslation'], …, 'Index', 'Index'))
            ->then(function (Kit $kit) {

                return $this->doTitle(
                    $kit,
                    $kit->view->getTranslation('Index')
                              ->_('Index')
                );
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Welcome'))
            ->then(function (Kit $kit) {
                $feedURL = $kit->router->unroute('blog') . 'atom.xml';

                if (false !== $feed = @file_get_contents($feedURL)) {
                    $stream = new Stringbuffer\Read();
                    $stream->initializeWith($feed);
                    $xml  = new Xml\Read($stream);
                    $data = [];
                    $max  = 5;

                    foreach ($xml->channel->item as $item) {
                        $data[] = [
                            'title' => (string) $item->title,
                            'date'  => strtotime((string) $item->pubDate),
                            'url'   => (string) $item->link
                        ];

                        if (0 >= --$max) {
                            break;
                        }
                    }

                    $kit->data->blog = $data;
                }

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);
    }
}
