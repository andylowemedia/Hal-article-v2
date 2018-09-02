<?php
namespace Console\Command;

use Elasticsearch\Client as ElasticsearchClient;
use Interop\Container\ContainerInterface;

class CreateArticleHistoryIndexCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return (new CreateArticleHistoryIndexCommand)
                ->setElasticsearchClient($container->get(ElasticsearchClient::class));
    }
}
