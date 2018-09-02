<?php
namespace App\Elasticsearch;

use Interop\Container\ContainerInterface;
use Elasticsearch\ClientBuilder;

class ClientFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return ClientBuilder::create()
                ->setHosts($config['elasticsearch']['hosts'])
                ->build();
    }
}
