<?php
namespace Console\Command;

use Elasticsearch\Client as ElasticsearchClient;
use Interop\Container\ContainerInterface;

class BuildArticlesIndexCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new BuildArticlesIndexCommand(
            $container->get(ElasticsearchClient::class),
            $container->get('ArticlesDbAdapter'),
            $container->get('config')['api']
        );
    }
}
