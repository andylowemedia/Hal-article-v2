<?php
/**
 * @see       https://github.com/zendframework/zend-expressive for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-expressive/blob/master/LICENSE.md New BSD License
 */

namespace App\Middleware\Error;

use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response;

class NotFoundDelegateFactory
{
    /**
     * @param ContainerInterface $container
     * @return NotFoundDelegate
     */
    public function __invoke(ContainerInterface $container)
    {
        return new NotFoundDelegate(new Response());
    }
}
