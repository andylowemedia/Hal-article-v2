<?php

namespace AppTest\Model;

use App\Model\ModelAbstract;
use App\ResultSet\ResultSetAbstract;
use PHPUnit\Framework\TestCase;

class ModelAbstractTest extends TestCase
{
    public function testSetAndGetMagicMethodsWorkAsExpected()
    {
        $model = $this->getMockBuilder(ModelAbstract::class)
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

    /**
     * @expectedException \App\Model\ModelException
     */
    public function testSetMagicMethodThrowExceptionWhenMethodDoesNotExists()
    {
        $model = $this->getMockBuilder(ModelAbstract::class)
            ->getMockForAbstractClass();
        $model->id = 1;
    }

    /**
     * @expectedException \App\Model\ModelException
     */
    public function testGetMagicMethodThrowExceptionWhenMethodDoesNotExists()
    {
        $model = $this->getMockBuilder(ModelAbstract::class)
            ->getMockForAbstractClass();
        $model->id;
    }

    public function testExchangeArrayMapsDataCorrectly()
    {
        $model = $this->getMockBuilder(ModelAbstract::class)
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
        $model = $this->getMockBuilder(ModelAbstract::class)
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
        $submodel = new UnitTestModel();

        $submodel->id = 1;

        $rows = $this->getMockBuilder(ResultSetAbstract::class)
            ->setMethods(['toArray'])
            ->getMockForAbstractClass();

        $rows->expects($this->once())
            ->method('toArray')
            ->willReturn([
                ['slug' => 'something']
            ]);

        $model = new UnitTestModel();
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
        $submodel = new UnitTestModel();
        $submodel->id = 1;

        $model = new UnitTestModel();

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
