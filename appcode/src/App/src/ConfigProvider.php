<?php

namespace App;

use App\Aws\SqsEvents;
use App\Handler\RelatedHandlerFactory;
use App\Handler\RelatedHandler;
use App\Mapper\MapperFactory;
use App\Mapper\SourceHistoryMapper;
use App\Query\Search;
use App\Query\SearchFactory;
use App\Query\Summary;
use App\Query\SummaryFactory;
use Aws\Sqs\SqsClient;
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
                Handler\ViewHandler::class        => Handler\ViewHandlerFactory::class,
                Handler\AddHandler::class         => Handler\AddHandlerFactory::class,
                Handler\EditHandler::class        => Handler\EditHandlerFactory::class,
                Handler\DeleteHandler::class      => Handler\DeleteHandlerFactory::class,
                Handler\SummaryHandler::class     => Handler\SummaryHandlerFactory::class,
                Handler\SearchHandler::class      => Handler\SearchHandlerFactory::class,
                Handler\CategoryHandler::class    => Handler\CategoryHandlerFactory::class,
                Handler\CustomFeedHandler::class  => Handler\CustomFeedHandlerFactory::class,
                Handler\HistoryAddHandler::class  => Handler\HistoryAddHandlerFactory::class,
                Handler\RelatedHandler::class     => Handler\RelatedHandlerFactory::class,
                Handler\UpdateSocialPostsHandler::class => Handler\UpdateSocialPostsHandlerFactory::class,

                Mapper\ArticleMapper::class           => Mapper\MapperFactory::class,
                Mapper\ArticleImageMapper::class      => Mapper\MapperFactory::class,
                Mapper\ArticleMediaMapper::class      => Mapper\MapperFactory::class,
                Mapper\CategoryMapper::class          => Mapper\MapperFactory::class,
                Mapper\ArticleCategoryMapper::class   => Mapper\MapperFactory::class,
                Mapper\ArticleKeywordMapper::class    => Mapper\MapperFactory::class,
                Mapper\FeaturedArticleMapper::class   => Mapper\MapperFactory::class,
                Mapper\SourceHistoryMapper::class     => Mapper\MapperFactory::class,
                Mapper\SocialMediaMapper::class       => Mapper\MapperFactory::class,
                Mapper\ArticleSocialMediaPostMapper::class => Mapper\MapperFactory::class,

                ElasticsearchClient::class      => Elasticsearch\ClientFactory::class,
                Summary::class                  => SummaryFactory::class,
                Search::class                   => SearchFactory::class,
                SqsClient::class                => SqsEvents::class
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
