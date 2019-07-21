<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;
use App\Mapper\Article as ArticleMapper;
use App\Mapper\ArticleImage as ArticleImageMapper;
use App\Mapper\ArticleMedia as ArticleMediaMapper;
use App\Mapper\ArticleCategory as ArticleCategoryMapper;
use App\Mapper\ArticleKeyword as ArticleKeywordMapper;
use App\Mapper\FeaturedArticle as FeaturedArticleMapper;

class EditFactory
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

        return new EditHandler(
            $config['elasticsearch']['hosts'],
            $config['api'],
            $config['featured']['sites'],
            $dbAdapter,
            $articleMapper,
            $articleImageMapper,
            $articleMediaMapper,
            $articleCategoryMapper,
            $articleKeywordMapper,
            $featuredArticleMapper
        );
    }
}
