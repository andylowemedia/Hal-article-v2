<?php

namespace App\Handler;

use App\Query\Search;
use Interop\Container\ContainerInterface;

class SearchHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $client = $container->get(Search::class);

        return new SearchHandler($client);
    }
}
