<?php

declare(strict_types=1);

namespace AppTest\ResultSet;

use App\ResultSet\ResultSetAbstract;
use PHPUnit\Framework\TestCase;

class ResultSetTest extends TestCase
{
    public function testResultSetElasticsearchInitialiseWorks()
    {
        $resultSet = $this->getMockBuilder(ResultSetAbstract::class)
            ->getMockForAbstractClass();


        $resultSet->elasticsearchInitialize($this->createTestData());

        $data = new \ArrayObject([
            'id' => 1,
            'title' => 'Test title for article'
        ]);

        $this->assertEquals($data, $resultSet->current());
    }

    public function testResultSetElasticsearchInitialiseFailsWhenDataSourceIsNotArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $resultSet = $this->getMockBuilder(ResultSetAbstract::class)
            ->getMockForAbstractClass();


        $resultSet->elasticsearchInitialize(new \stdClass());
    }

    private function createTestData()
    {
        return [
            'total' => 200,
            'hits' => [
                [
                    '_id' => 1,
                    '_source' => [
                        'title' => 'Test title for article'
                    ]
                ]
            ]
        ];
    }
}

