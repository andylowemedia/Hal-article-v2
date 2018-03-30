<?php

namespace App;

use Elasticsearch\Client as ElasticsearchClient;

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
//            'templates'    => $this->getTemplates(),
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
            'factories'  => [
                Action\ViewAction::class        => Action\ViewFactory::class,
                Action\AddAction::class         => Action\AddFactory::class,
                Action\SummaryAction::class     => Action\SummaryFactory::class,
                Action\SearchAction::class      => Action\SearchFactory::class,
                Action\CategoryAction::class    => Action\CategoryFactory::class,
                Action\CustomFeedAction::class  => Action\CustomFeedFactory::class,
                Action\HistoryAddAction::class  => Action\HistoryAddFactory::class,
                
                Mapper\Article::class           => Mapper\MapperFactory::class,
                Mapper\ArticleImage::class      => Mapper\MapperFactory::class,
                Mapper\ArticleMedia::class      => Mapper\MapperFactory::class,
                Mapper\Category::class          => Mapper\MapperFactory::class,
                Mapper\ArticleCategory::class   => Mapper\MapperFactory::class,
                Mapper\ArticleKeyword::class    => Mapper\MapperFactory::class,
                Mapper\FeaturedArticle::class   => Mapper\MapperFactory::class,
                Mapper\SourceHistory::class     => Mapper\MapperFactory::class,
                ElasticsearchClient::class      => Elasticsearch\ClientFactory::class,
            ],
//            'abstract_factories' => [
//                Mapper\MapperFactory::class
//            ]
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
//    public function getTemplates()
//    {
//        return [
//            'paths' => [
//                'app'    => [__DIR__ . '/../templates/app'],
//                'error'  => [__DIR__ . '/../templates/error'],
//                'layout' => [__DIR__ . '/../templates/layout'],
//            ],
//        ];
//    }
}
