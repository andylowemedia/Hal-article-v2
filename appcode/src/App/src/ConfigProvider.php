<?php

namespace App;

use App\Handler\RelatedFactory;
use App\Handler\RelatedHandler;
use App\Mapper\MapperFactory;
use App\Mapper\SourceHistory;
use App\Query\Search;
use App\Query\SearchFactory;
use App\Query\Summary;
use App\Query\SummaryFactory;
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
                Handler\ViewHandler::class        => Handler\ViewFactory::class,
                Handler\AddHandler::class         => Handler\AddFactory::class,
                Handler\EditHandler::class        => Handler\EditFactory::class,
                Handler\DeleteHandler::class      => Handler\DeleteFactory::class,
                Handler\SummaryHandler::class     => Handler\SummaryFactory::class,
                Handler\SearchHandler::class      => Handler\SearchFactory::class,
                Handler\CategoryHandler::class    => Handler\CategoryFactory::class,
                Handler\CustomFeedHandler::class  => Handler\CustomFeedFactory::class,
                Handler\HistoryAddHandler::class  => Handler\HistoryAddFactory::class,
                Handler\RelatedHandler::class     => Handler\RelatedFactory::class,
                Handler\UpdateSocialPostsHandler::class => Handler\UpdateSocialPostsHandlerFactory::class,

                Mapper\Article::class           => Mapper\MapperFactory::class,
                Mapper\ArticleImage::class      => Mapper\MapperFactory::class,
                Mapper\ArticleMedia::class      => Mapper\MapperFactory::class,
                Mapper\Category::class          => Mapper\MapperFactory::class,
                Mapper\ArticleCategory::class   => Mapper\MapperFactory::class,
                Mapper\ArticleKeyword::class    => Mapper\MapperFactory::class,
                Mapper\FeaturedArticle::class   => Mapper\MapperFactory::class,
                Mapper\SourceHistory::class     => Mapper\MapperFactory::class,
                Mapper\SocialMedia::class       => Mapper\MapperFactory::class,
                Mapper\ArticleSocialMediaPost::class => Mapper\MapperFactory::class,

                ElasticsearchClient::class      => Elasticsearch\ClientFactory::class,
                Summary::class                  => SummaryFactory::class,
                Search::class                   => SearchFactory::class
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
