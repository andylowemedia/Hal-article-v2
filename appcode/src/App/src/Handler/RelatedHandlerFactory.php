<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class RelatedHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new RelatedHandler($config['elasticsearch']['hosts']);
    }
}
