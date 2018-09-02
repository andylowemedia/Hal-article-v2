<?php
namespace Console\Command;

use Elasticsearch\Client as ElasticsearchClient;
use Interop\Container\ContainerInterface;

class DeleteIndexCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return (new DeleteIndexCommand)
                ->setElasticsearchClient($container->get(ElasticsearchClient::class));
    }
}
