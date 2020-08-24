<?php

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
            'type' => 'article'
        ]);

        $expected = [
            'index' => 'articles',
            'type' => 'article',
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
//                'track_scores' => 1,
//                'min_score' => 1,
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
            'type' => 'article',
            'page' => 0
        ]);

        $expected = [
            'index' => 'articles',
            'type' => 'article',
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
//                'track_scores' => 1,
//                'min_score' => 1,
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

    public function testBuildingParamsThrowsExceptionWhenMissingType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->queryAbstract->buildClientParams([
            'index' => 'articles',
        ]);
    }
}
