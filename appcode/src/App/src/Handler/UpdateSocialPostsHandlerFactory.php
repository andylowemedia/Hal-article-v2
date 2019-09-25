<?php

namespace App\Handler;

use App\Mapper\ArticleSocialMediaPost;
use App\Mapper\SocialMedia;
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
            $container->get(ArticleSocialMediaPost::class),
            $container->get(SocialMedia::class)
        );
    }
}
