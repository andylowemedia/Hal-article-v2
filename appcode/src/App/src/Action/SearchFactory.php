<?php

namespace App\Action;

use Interop\Container\ContainerInterface;

class SearchFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        
        return new SearchAction($config['elasticsearch']['hosts']);
    }
}
