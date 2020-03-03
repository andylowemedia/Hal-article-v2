<?php
namespace App\Handler;

use Interop\Container\ContainerInterface;
use Elasticsearch\Client as ElasticsearchClient;

class CustomFeedHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $elasticsearchClient = $container->get(ElasticsearchClient::class);

        return new CustomFeedHandler($elasticsearchClient);
    }
}
