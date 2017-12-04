<?php

namespace App\Action;

use Interop\Container\ContainerInterface;

class ViewFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new ViewAction($config['elasticsearch']['hosts']);
    }
}
