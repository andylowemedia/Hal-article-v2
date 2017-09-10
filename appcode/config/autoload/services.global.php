<?php
return [
    'dependencies' => [
        'factories' => [
            App\Mapper\Article::class           => App\Mapper\MapperFactory::class,
            App\Mapper\ArticleImage::class      => App\Mapper\MapperFactory::class,
            App\Mapper\Category::class          => App\Mapper\MapperFactory::class,
            App\Mapper\ArticleCategory::class   => App\Mapper\MapperFactory::class,
            App\Mapper\FeaturedArticle::class   => App\Mapper\MapperFactory::class,
            App\Mapper\SourceHistory::class     => App\Mapper\MapperFactory::class,
        ]
    ],
];