<?php

namespace AppTest\Mapper;

use App\Mapper\ArticleMapper;
use App\Mapper\MapperFactory;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Laminas\Db\Adapter\AdapterInterface;

class MapperFactoryTest extends TestCase
{
    public function testFactoryReturnsExpectedMapperInstance()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->onlyMethods(['get'])
            ->getMockForAbstractClass();

        $adapter = $this->getMockBuilder(AdapterInterface::class)
            ->getMockForAbstractClass();

        $container->expects($this->once())
            ->method('get')
            ->willReturn($adapter);

        $result = (new MapperFactory())($container, ArticleMapper::class);

        $this->assertInstanceOf(ArticleMapper::class, $result);
    }
}
