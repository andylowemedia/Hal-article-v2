<?php

namespace App\Query;


use Elasticsearch\Client;
use Interop\Container\ContainerInterface;

class SearchFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return (new Search)->setClient($container->get(Client::class));
    }
}
