<?php

namespace App\Action;

use Interop\Container\ContainerInterface;

class SummaryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        
        return new SummaryAction($config['elasticsearch']['hosts']);
    }
}
