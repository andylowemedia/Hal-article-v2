<?php
namespace Console\Command;

use Elasticsearch\Client as ElasticsearchClient;
use Interop\Container\ContainerInterface;

class BuildArticlesIndexCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return (new BuildArticlesIndexCommand)
                ->setElasticsearchClient($container->get(ElasticsearchClient::class))
                ->setArticlesDbAdapter($container->get('ArticlesDbAdapter'));
    }
}