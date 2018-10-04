<?php
/**
 * @see       https://github.com/zendframework/zend-expressive for the canonical source repository
 * @copyright Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive/blob/master/LICENSE.md New BSD License
 */

namespace App\Middleware\Error;

use Psr\Container\ContainerInterface;

class JsonErrorResponseGeneratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return JsonErrorResponseGenerator
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        $debug = isset($config['debug']) ? $config['debug'] : false;

        return new JsonErrorResponseGenerator($debug);
    }
}
