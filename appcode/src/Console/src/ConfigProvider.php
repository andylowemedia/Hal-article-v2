<?php

namespace Console;
use Console\Command;
/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'invokables' => [
            ],
            'factories' => [
                Command\CreateIndexCommand::class               => Command\CreateIndexCommandFactory::class,
                Command\CreateArticleHistoryIndexCommand::class => Command\CreateArticleHistoryIndexCommandFactory::class,
                Command\DeleteIndexCommand::class               => Command\DeleteIndexCommandFactory::class,
                Command\BuildArticlesIndexCommand::class        => Command\BuildArticlesIndexCommandFactory::class,
                Command\BuildArticleHistoryIndexCommand::class  => Command\BuildArticleHistoryIndexCommandFactory::class,
            ]
        ];
    }
}
