<?php

namespace App\Action;

use Interop\Container\ContainerInterface;

class HomePageFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        return new HomePageAction($config['elasticsearch']['hosts']);
    }
}
