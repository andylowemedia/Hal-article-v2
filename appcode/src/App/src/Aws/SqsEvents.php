<?php

declare(strict_types=1);

namespace App\Aws;

use Aws\Sqs\SqsClient;
use Psr\Container\ContainerInterface;

class SqsEvents
{
    public function __invoke(ContainerInterface $container): SqsClient
    {
        return new SqsClient(
            $container->get('config')['queue']['events']['client']
        );
    }
}