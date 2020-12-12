<?php
declare(strict_types=1);

namespace AppTest\Query;


use App\Query\QueryAbstract;
use Elasticsearch\Client;
use PHPUnit\Framework\TestCase;

class QueryAbstractTest extends TestCase
{
    private $queryAbstract;

    public function setUp(): void
    {
        parent::setUp();

        $this->queryAbstract = $this->getMockBuilder(QueryAbstract::class)
            ->getMockForAbstractClass();
    }

    public function testSetAndGetClientMethodsWork()
    {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryAbstract->setClient($client);

        $this->assertEquals($client, $this->queryAbstract->getClient());
    }

    public function testBuildingParamsBasedOnParams()
    {
        $this->queryAbstract->buildClientParams([
            'index' => 'articles',
        ]);

        $expected = [
            'index' => 'articles',
            'size' => 102,
            'from' => 0,
            'body' => [
                '_source' => [
                    'id',
                    'slug',
                    'title',
                    'subtitle',
                    'summary',
                    'image',
                    'publishDate',
                    'author',
                    'source',
                    'categories',
                    'displayCategories',
                    'keywords',
                    'posted',
                    'url'
                ],
                'query' => [
                    'bool' => []
                ]
            ]
        ];

        $this->assertEquals($expected, $this->queryAbstract->getParams());
    }

    public function testBuildingParamsBasedOnParamsPage()
    {
        $this->queryAbstract->buildClientParams([
            'index' => 'articles',
            'page' => 0
        ]);

        $expected = [
            'index' => 'articles',
            'size' => 102,
            'from' => 0,
            'body' => [
                '_source' => [
                    'id',
                    'slug',
                    'title',
                    'subtitle',
                    'summary',
                    'image',
                    'publishDate',
                    'author',
                    'source',
                    'categories',
                    'displayCategories',
                    'keywords',
                    'posted',
                    'url'
                ],
                'query' => [
                    'bool' => []
                ]
            ]
        ];

        $this->assertEquals($expected, $this->queryAbstract->getParams());
    }

    public function testBuildingParamsThrowsExceptionWhenMissingIndex()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->queryAbstract->buildClientParams([]);
    }
}
