<?php
namespace App\Handler;

use Interop\Container\ContainerInterface;
use App\Mapper\SourceHistory as SourceHistoryMapper;
use Elasticsearch\Client as ElasticsearchClient;
use Zend\Db\Adapter\Adapter;

class HistoryAddFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new HistoryAddHandler(
            $container->get(SourceHistoryMapper::class),
            $container->get(ElasticsearchClient::class),
            $container->get('ArticlesDbAdapter')
        );
    }
}
