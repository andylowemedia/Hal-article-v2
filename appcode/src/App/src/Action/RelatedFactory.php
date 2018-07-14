<?php

namespace App\Action;

use Interop\Container\ContainerInterface;

class RelatedFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        
        return new RelatedAction($config['elasticsearch']['hosts']);
    }
}
