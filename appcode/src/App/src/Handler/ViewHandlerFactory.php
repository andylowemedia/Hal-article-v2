<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class ViewHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new ViewHandler($config['elasticsearch']['host']);
    }
}
