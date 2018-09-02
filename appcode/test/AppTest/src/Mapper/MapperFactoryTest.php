<?php

namespace AppTest\Mapper;

use App\Mapper\Article;
use App\Mapper\MapperFactory;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Zend\Db\Adapter\AdapterInterface;

class MapperFactoryTest extends TestCase
{
    public function testFactoryReturnsExpectedMapperInstance()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $adapter = $this->getMockBuilder(AdapterInterface::class)
            ->getMockForAbstractClass();

        $container->expects($this->once())
            ->method('get')
            ->willReturn($adapter);

        $result = (new MapperFactory())($container, Article::class);

        $this->assertInstanceOf(Article::class, $result);
    }
}
