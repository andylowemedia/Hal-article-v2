<?php
namespace Console\Command;

use Elasticsearch\Client as ElasticsearchClient;
use Interop\Container\ContainerInterface;

class BuildArticleHistoryIndexCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return (new BuildArticleHistoryIndexCommand)
                ->setElasticsearchClient($container->get(ElasticsearchClient::class))
                ->setArticlesDbAdapter($container->get('ArticlesDbAdapter'));
    }
}