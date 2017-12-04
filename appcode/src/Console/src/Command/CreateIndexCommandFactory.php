<?php
namespace Console\Command;

use Elasticsearch\Client as ElasticsearchClient;
use Interop\Container\ContainerInterface;

class CreateIndexCommandFactory
{
    public function __invoke(ContainerInterface $container) {
        return (new CreateIndexCommand)
                ->setElasticsearchClient($container->get(ElasticsearchClient::class));
    }
    
}