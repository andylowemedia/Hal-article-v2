<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use App\Mapper\Article as ArticleMapper;
use App\Mapper\ArticleImage as ArticleImageMapper;
use App\Mapper\ArticleCategory as ArticleCategoryMapper;
use App\Mapper\FeaturedArticle as FeaturedArticleMapper;


class AddFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $dbAdapter = $container->get('ArticlesDbAdapter');
        $articleMapper = $container->get(ArticleMapper::class);
        $articleImageMapper = $container->get(ArticleImageMapper::class);
        $articleCategoryMapper = $container->get(ArticleCategoryMapper::class);
        $featuredArticleMapper = $container->get(FeaturedArticleMapper::class);
        
        return new AddAction($config['elasticsearch']['hosts'], $config['api'], $config['featured']['sites'], $dbAdapter, $articleMapper, $articleImageMapper, $articleCategoryMapper, $featuredArticleMapper);
    }
}
