<?php

namespace App\Handler;

use Aws\Sqs\SqsClient;
use Interop\Container\ContainerInterface;
use App\Mapper\ArticleMapper as ArticleMapper;
use App\Mapper\ArticleImageMapper as ArticleImageMapper;
use App\Mapper\ArticleMediaMapper as ArticleMediaMapper;
use App\Mapper\ArticleCategoryMapper as ArticleCategoryMapper;
use App\Mapper\ArticleKeywordMapper as ArticleKeywordMapper;
use App\Mapper\FeaturedArticleMapper as FeaturedArticleMapper;

class AddHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $dbAdapter = $container->get('ArticlesDbAdapter');
        $articleMapper = $container->get(ArticleMapper::class);
        $articleImageMapper = $container->get(ArticleImageMapper::class);
        $articleMediaMapper = $container->get(ArticleMediaMapper::class);
        $articleCategoryMapper = $container->get(ArticleCategoryMapper::class);
        $articleKeywordMapper = $container->get(ArticleKeywordMapper::class);
        $featuredArticleMapper = $container->get(FeaturedArticleMapper::class);
        $sqsClient = $container->get(SqsClient::class);

        return new AddHandler(
            [$config['elasticsearch']['host']],
            $config['api'],
            $config['featured']['sites'],
            $dbAdapter,
            $articleMapper,
            $articleImageMapper,
            $articleMediaMapper,
            $articleCategoryMapper,
            $articleKeywordMapper,
            $featuredArticleMapper,
            $sqsClient,
            $config['queue']['events']['queue']
        );
    }
}
