<?php

namespace App\Query;

use Elasticsearch\Client;
use Interop\Container\ContainerInterface;

class SummaryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return (new Summary())->setClient($container->get(Client::class));
    }
}
