<?php

namespace AppTest\Entity;

use App\Entity\EntityAbstract;
use App\ResultSet\ResultSetAbstract;
use PHPUnit\Framework\TestCase;

class EntityAbstractTest extends TestCase
{
    public function testSetAndGetMagicMethodsWorkAsExpected()
    {
        $model = $this->getMockBuilder(EntityAbstract::class)
            ->setMethods(['setId', 'getId'])
            ->getMockForAbstractClass();

        $model->expects($this->once())
            ->method('setId')
            ->willReturn($model);

        $model->expects($this->once())
            ->method('getId')
            ->willReturn(1);


        $model->id = 1;
        $model->id;
    }

    public function testSetMagicMethodThrowExceptionWhenMethodDoesNotExists()
    {
        $this->expectException(\App\Entity\EntityException::class);
        $model = $this->getMockBuilder(EntityAbstract::class)
            ->getMockForAbstractClass();
        $model->id = 1;
    }

    public function testGetMagicMethodThrowExceptionWhenMethodDoesNotExists()
    {
        $this->expectException(\App\Entity\EntityException::class);
        $model = $this->getMockBuilder(EntityAbstract::class)
            ->getMockForAbstractClass();
        $model->id;
    }

    public function testExchangeArrayMapsDataCorrectly()
    {
        $model = $this->getMockBuilder(EntityAbstract::class)
            ->setMethods(['setId'])
            ->getMockForAbstractClass();

        $model->expects($this->once())
            ->method('setId')
            ->willReturn($model);

        $model->exchangeArray([
            'id' => 1
        ]);
    }

    public function testExchangeArrayMapsDataCorrectlyAndRemovesUnderscore()
    {
        $model = $this->getMockBuilder(EntityAbstract::class)
            ->setConstructorArgs([['article_id' => 1]])
            ->setMethods(['setArticleId'])
            ->getMockForAbstractClass();

        $model->expects($this->once())
            ->method('setArticleId')
            ->willReturn($model);

        $model->exchangeArray([
            'article_id' => 1
        ]);
    }

    public function testToArrayReturnsExpectedArray()
    {
        $submodel = new UnitTestEntity();

        $submodel->id = 1;

        $rows = $this->getMockBuilder(ResultSetAbstract::class)
            ->setMethods(['toArray'])
            ->getMockForAbstractClass();

        $rows->expects($this->once())
            ->method('toArray')
            ->willReturn([
                ['slug' => 'something']
            ]);

        $model = new UnitTestEntity();
        $model->id = 1;
        $model->rows = $rows;
        $model->something = $submodel;

        $expected = [
            'id' => 1,
            'something' => ['id' => 1, 'rows'=> null, 'something' => null],
            'rows' => [
                ['slug' => 'something']
            ]
        ];

        $this->assertEquals($expected, $model->toArray());
    }

    public function testToArraySqlReturnsExpectedArray()
    {
        $submodel = new UnitTestEntity();
        $submodel->id = 1;

        $model = new UnitTestEntity();

        $model->id = 1;
        $model->something = $submodel;

        $expected = [
            'id' => 1,
            'something' => ['id' => 1, 'rows'=> null, 'something' => null],
            'rows' => null
        ];

        $this->assertEquals($expected, $model->toArraySql());
    }
}
