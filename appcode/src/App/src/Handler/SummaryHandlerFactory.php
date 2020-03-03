<?php

namespace App\Handler;

use App\Query\Summary;
use Interop\Container\ContainerInterface;

class SummaryHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $client = $container->get(Summary::class);

        return new SummaryHandler($client);
    }
}
