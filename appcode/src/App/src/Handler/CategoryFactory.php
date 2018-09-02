<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class CategoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new CategoryHandler($config['api'], $config['elasticsearch']['hosts']);
    }
}
