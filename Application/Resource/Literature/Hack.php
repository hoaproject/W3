<?php

namespace Application\Resource\Literature;

use Application\Dispatcher\Kit;
use Application\Resource;
use Hoa\Promise;
use Hoa\Router;
use League\CommonMark\CommonMarkConverter;

class Hack extends Resource
{
    public function get(Kit $_this, $chapter)
    {
        $_this
            ->promise
            ->then(function (Kit $kit) use ($chapter) {
                $subPromise = new Promise(function ($fulfill) use ($kit) {
                    $fulfill($kit);
                });
                $subPromise
                    ->then(curry([$this, 'doTranslation'], …, 'Literature', 'Literature'))
                    ->then(function (Kit $kit) use ($chapter) {
                        return $this->doTitle(
                            $kit,
                            'Hoa\\' . ucfirst($chapter) .
                            $kit->view->getTranslation('Literature')
                                      ->_(', hack book')
                        );
                    });

                return $subPromise;
            })
            ->then(curry([$this, 'doMainOverlay'], …, 'Literature/Hack'))
            ->then(function (Kit $kit) use ($chapter) {
                $chapter  = ucfirst($chapter);
                $language = ucfirst($kit->user->getLocale()->getLanguage());

                $kit->data->chapter = $chapter;
                $root               = '/usr/local/lib/Hoa/' . $chapter . '/';
                $hackChapter        = $root . 'Documentation/';
                $hackIndex          = $hackChapter . $language . '/Index.xyl';
                $readme             = $root . 'README.md';

                if (false === file_exists($root)) {
                    throw new Router\Exception\NotFound('Library does not exist.');
                }

                if (false === file_exists($hackChapter)) {
                    if (false === file_exists($readme)) {
                        throw new Router\Exception\NotFound(
                            'This library has no documentation yet.'
                        );
                    }

                    $readmeContent = file_get_contents($readme);
                    $readmeContent = preg_replace(
                        [
                            // Remove introduction but keep the abstract.
                            '/^.*# Hoa[^\n]+\n+/s',
                            // Remove Installation Section.
                            '/## Installation.*(?=## Quick usage)/s',
                            // Remove Documentation and next sections.
                            '/\n+## Documentation.*$/s'
                        ],
                        '',
                        $readmeContent
                    );

                    $converter      = new CommonMarkConverter();
                    $readmeCompiled = $converter->convertToHtml($readmeContent);

                    $kit->view->addOverlay('hoa://Application/View/Shared/Literature/Hack/Readme.xyl');
                    $kit->data->readme = $readmeCompiled;
                } else {
                    if (false === file_exists($hackIndex)) {
                        throw new Router\Exception\NotFound(
                            'Hack book chapter does not exist in this language.'
                        );
                    }

                    $kit->view->addOverlay($hackIndex);
                }

                return $kit;
            })
            ->then([$this, 'doFooter'])
            ->then([$this, 'doRender']);

        return;
    }
}
