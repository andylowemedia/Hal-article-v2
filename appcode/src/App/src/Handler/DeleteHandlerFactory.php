<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class DeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DeleteHandler();
    }
}
