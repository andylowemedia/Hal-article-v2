<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class SummaryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new SummaryHandler($config['elasticsearch']['hosts']);
    }
}
