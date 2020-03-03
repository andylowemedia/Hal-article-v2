<?php

namespace App\Handler;

use App\Mapper\ArticleSocialMediaPostMapper;
use App\Mapper\SocialMediaMapper;
use Psr\Container\ContainerInterface;

class UpdateSocialPostsHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $dbAdapter = $container->get('ArticlesDbAdapter');

        return new UpdateSocialPostsHandler(
            $config['elasticsearch']['hosts'],
            $config['api'],
            $dbAdapter,
            $container->get(ArticleSocialMediaPostMapper::class),
            $container->get(SocialMediaMapper::class)
        );
    }
}
