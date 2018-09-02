<?php

namespace App\Handler;

use Interop\Container\ContainerInterface;

class DeleteFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DeleteHandler();
    }
}
