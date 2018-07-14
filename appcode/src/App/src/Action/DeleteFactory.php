<?php

namespace App\Action;

use Interop\Container\ContainerInterface;


class DeleteFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DeleteAction();
    }
}
