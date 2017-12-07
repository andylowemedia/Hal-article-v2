<?php
namespace App\Action;

use Interop\Container\ContainerInterface;
use Elasticsearch\Client as ElasticsearchClient;

class CustomFeedFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $elasticsearchClient = $container->get(ElasticsearchClient::class);
        
        return new CustomFeedAction($elasticsearchClient);
    }
}