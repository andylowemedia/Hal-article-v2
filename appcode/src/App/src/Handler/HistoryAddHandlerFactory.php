<?php
namespace App\Handler;

use Interop\Container\ContainerInterface;
use App\Mapper\SourceHistoryMapper as SourceHistoryMapper;

class HistoryAddHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new HistoryAddHandler(
            $container->get(SourceHistoryMapper::class),
            $config['elasticsearch']['hosts'],
            $container->get('ArticlesDbAdapter')
        );
    }
}
