<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class SearchFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new SearchHandler($config['elasticsearch']['hosts']);
    }
}
