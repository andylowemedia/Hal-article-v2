<?php

namespace App\Action;

use Interop\Container\ContainerInterface;

class CategoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        
        return new CategoryAction($config['api'], $config['elasticsearch']['hosts']);
    }
}
