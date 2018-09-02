<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class ViewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new ViewHandler($config['elasticsearch']['hosts']);
    }
}
