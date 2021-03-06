<?php

declare(strict_types=1);

namespace AppTest\Elasticsearch;

use App\Elasticsearch\ClientFactory;
use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ClientFactoryTest extends TestCase
{
    public function testElasticSearchClientIsProduced()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get', 'has'])
            ->getMock();

        $config = [
            'elasticsearch' => [
                'host' => '10.211.55.29:9200'
            ]
        ];

        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $expectedClientObject = ClientBuilder::create()
            ->setHosts([$config['elasticsearch']['host']])
            ->build();

        $client = (new ClientFactory())($container);

        $this->assertEquals($expectedClientObject, $client);
    }
}
