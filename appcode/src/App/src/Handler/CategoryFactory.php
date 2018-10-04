<?php

namespace App\Handler;

use App\Query\Search;
use Interop\Container\ContainerInterface;

class CategoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        $search = $container->get(Search::class);

        return new CategoryHandler($config['api'], $search);
    }
}
