<?php

declare(strict_types=1);

namespace AppTest\Mapper;

use App\Mapper\MapperAbstract;
use App\Mapper\MapperException;
use App\Entity\EntityAbstract;
use App\ResultSet\ResultSetAbstract;
use PHPUnit\Framework\TestCase;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\Adapter\Driver\StatementInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;
use Laminas\Db\TableGateway\TableGateway;

class MapperAbstractTest extends TestCase
{
    private $dbAdapter;

    public function setUp(): void
    {
        parent::setUp();

        $this->dbAdapter = $this->getMockBuilder(AdapterInterface::class)
            ->getMockForAbstractClass();
    }

    public function testSetAndGetTableGatewayMethodsWorkAsExpected()
    {
        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapperAbstract->setTableGateWay($tableGateWay);

        $this->assertEquals($tableGateWay, $mapperAbstract->getTableGateWay());
    }

    public function testSetAndGetTableNameMethodsWorkAsExpected()
    {
        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $tableName = 'articles';

        $mapperAbstract->setTableName($tableName);

        $this->assertEquals($tableName, $mapperAbstract->getTableName());
    }

    public function testCountMakesACountQuery()
    {
        $result = $this->getMockBuilder(ResultInterface::class)
            ->setMethods(['current'])
            ->getMockForAbstractClass();

        $expectedCount = 1;
        $result->expects($this->once())
            ->method('current')
            ->willReturn([
                'count' => $expectedCount
            ]);

        $statement = $this->getMockBuilder(StatementInterface::class)
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $statement->expects($this->once())
            ->method('execute')
            ->willReturn($result)
        ;

        $sql = $this->getMockBuilder(Sql::class)
            ->setConstructorArgs([$this->dbAdapter])
            ->setMethods(['prepareStatementForSqlObject'])
            ->getMock()
        ;

        $select = (new Sql($this->dbAdapter))->select()
            ->columns(['count'=>new Expression('count(id)')])
            ->where(['column = ?' => 1])->group('id desc');

        $sql->expects($this->once())
            ->method('prepareStatementForSqlObject')
            ->with($select)
            ->willReturn($statement);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSql'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('getSql')
            ->willReturn($sql);

        $mapperAbstract->setTableGateway($tableGateWay);

        $this->assertEquals($expectedCount, $mapperAbstract->count(['column = ?' => 1], 'id desc'));

    }

    public function testCountMakesACountQueryPassingZendDbSelect()
    {
        $result = $this->getMockBuilder(ResultInterface::class)
            ->setMethods(['current'])
            ->getMockForAbstractClass();

        $expectedCount = 1;
        $result->expects($this->once())
            ->method('current')
            ->willReturn([
                'count' => $expectedCount
            ]);

        $statement = $this->getMockBuilder(StatementInterface::class)
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $statement->expects($this->once())
            ->method('execute')
            ->willReturn($result)
        ;

        $sql = $this->getMockBuilder(Sql::class)
            ->setConstructorArgs([$this->dbAdapter])
            ->setMethods(['prepareStatementForSqlObject'])
            ->getMock()
        ;

        $select = (new Sql($this->dbAdapter))->select()
            ->columns(['count'=>new Expression('count(id)')])
            ->where(['column = ?' => 1])->group('id desc');

        $sql->expects($this->once())
            ->method('prepareStatementForSqlObject')
            ->with($select)
            ->willReturn($statement);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSql'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('getSql')
            ->willReturn($sql);

        $mapperAbstract->setTableGateway($tableGateWay);

        $this->assertEquals($expectedCount, $mapperAbstract->count($select));

    }

    public function testCountMakesACountQueryWithAResultWithNoCount()
    {
        $this->expectException(\App\Mapper\MapperException::class);

        $result = $this->getMockBuilder(ResultInterface::class)
            ->setMethods(['current'])
            ->getMockForAbstractClass();

        $result->expects($this->once())
            ->method('current')
            ->willReturn([
            ]);

        $statement = $this->getMockBuilder(StatementInterface::class)
            ->setMethods(['execute'])
            ->getMockForAbstractClass();

        $statement->expects($this->once())
            ->method('execute')
            ->willReturn($result)
        ;

        $sql = $this->getMockBuilder(Sql::class)
            ->setConstructorArgs([$this->dbAdapter])
            ->setMethods(['prepareStatementForSqlObject'])
            ->getMock()
        ;

        $select = (new Sql($this->dbAdapter))->select()
            ->columns(['count'=>new Expression('count(id)')])
            ->where(['column = ?' => 1])->group('id desc');

        $sql->expects($this->once())
            ->method('prepareStatementForSqlObject')
            ->with($select)
            ->willReturn($statement);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSql'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('getSql')
            ->willReturn($sql);

        $mapperAbstract->setTableGateway($tableGateWay);
        $mapperAbstract->count(['column = ?' => 1], 'id desc');
    }

    public function testFetchAllMethodMakesQueryCall()
    {
        $sql = new Sql($this->dbAdapter);

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSql', 'selectWith'])
            ->getMock();

        $select = $sql->select()
            ->where(['column = ?' => 1])
            ->order('id desc')
            ->limit(1)
            ->offset(1);

        $tableGateWay->expects($this->once())
            ->method('getSql')
            ->willReturn($sql);

        $result = $this->getMockBuilder(ResultSetAbstract::class)->getMock();

        $tableGateWay->expects($this->once())
            ->method('selectWith')
            ->with($select)
            ->willReturn($result)
        ;


        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);


        $mapperAbstract->fetchAll(['column = ?' => 1], 'id desc', 1, 1);
    }

    public function testFetchAllMethodMakesQueryCallWithZendDbSelect()
    {
        $sql = new Sql($this->dbAdapter);

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['selectWith'])
            ->getMock();

        $select = $sql->select()
            ->where(['column = ?' => 1])
            ->order('id desc')
            ->limit(1)
            ->offset(1);

        $result = $this->getMockBuilder(ResultSetAbstract::class)->getMock();

        $tableGateWay->expects($this->once())
            ->method('selectWith')
            ->with($select)
            ->willReturn($result)
        ;

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->fetchAll($select);
    }

    public function testFindMethodMakesQuery()
    {
        $result = $this->getMockBuilder(EntityAbstract::class)
            ->getMockForAbstractClass();

        $results = $this->getMockBuilder(ResultSet::class)
            ->setMethods(['current'])
            ->getMockForAbstractClass();

        $results->expects($this->once())
            ->method('current')
            ->willReturn($result);

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['select'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('select')
            ->with(['id' => 1])
            ->willReturn($results)
        ;

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->find(1);
    }

    public function testFindByParamsMethodMakesQuery()
    {
        $sql = new Sql($this->dbAdapter);

        $result = $this->getMockBuilder(EntityAbstract::class)
            ->getMockForAbstractClass();

        $results = $this->getMockBuilder(ResultSet::class)
            ->setMethods(['current'])
            ->getMockForAbstractClass();

        $results->expects($this->once())
            ->method('current')
            ->willReturn($result);

        $select = $sql->select()
            ->where(['column = ?' => 1])
            ->order('id desc');

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSql', 'selectWith'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('getSql')
            ->willReturn($sql);

        $tableGateWay->expects($this->once())
            ->method('selectWith')
            ->with($select)
            ->willReturn($results);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->findByParams(['column = ?' => 1], 'id desc');
    }

    public function testFindByParamsMethodMakesQueryWithZendDbSelect()
    {
        $sql = new Sql($this->dbAdapter);

        $result = $this->getMockBuilder(EntityAbstract::class)
            ->getMockForAbstractClass();

        $results = $this->getMockBuilder(ResultSet::class)
            ->setMethods(['current'])
            ->getMockForAbstractClass();

        $results->expects($this->once())
            ->method('current')
            ->willReturn($result);

        $select = $sql->select()
            ->where(['column = ?' => 1])
            ->order('id desc');

        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['selectWith'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('selectWith')
            ->with($select)
            ->willReturn($results);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->findByParams($select);
    }

    public function testSaveMethodForInserting()
    {
        $modelAbstract = $this->getMockBuilder(EntityAbstract::class)
            ->setMethods(['setId', 'getId', 'setFirstName', 'getFirstName', 'toArray'])
            ->getMockForAbstractClass();

        $modelAbstract->expects($this->any())
            ->method('getId')
            ->willReturn(0);

        $modelAbstract->expects($this->any())
            ->method('getFirstName')
            ->willReturn('something');

        $modelAbstract->firstName = 'something';


        $modelAbstract->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'firstName' => 'something',
                'something' => 'else'
            ]);


        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['insert'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('insert')
            ->with([
                'first_name' => 'something',
            ]);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->excludeList = ['something'];

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->save($modelAbstract);

    }

    public function testSaveMethodForInsertingWhereNoExcludeListHasBeenSet()
    {
        $this->expectException(\App\Mapper\MapperException::class);

        $modelAbstract = $this->getMockBuilder(EntityAbstract::class)
            ->setMethods(['setId', 'getId', 'setFirstName', 'getFirstName', 'toArray'])
            ->getMockForAbstractClass();

        $modelAbstract->expects($this->any())
            ->method('getId')
            ->willReturn(0);

        $modelAbstract->expects($this->any())
            ->method('getFirstName')
            ->willReturn('something');

        $modelAbstract->firstName = 'something';


        $modelAbstract->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'firstName' => 'something',
                'something' => 'else'
            ]);


        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->save($modelAbstract);

    }

    public function testSaveMethodForUpdating()
    {
        $modelAbstract = $this->getMockBuilder(EntityAbstract::class)
            ->setMethods(['setId', 'getId', 'setFirstName', 'getFirstName', 'toArray'])
            ->getMockForAbstractClass();

        $modelAbstract->expects($this->any())
            ->method('getId')
            ->willReturn(1);

        $modelAbstract->expects($this->any())
            ->method('getFirstName')
            ->willReturn('something');

        $modelAbstract->firstName = 'something';


        $modelAbstract->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'id'        => 1,
                'firstName' => 'something',
                'something' => 'else'
            ]);


        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['update'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('update')
            ->with([
                'id'         => 1,
                'first_name' => 'something',
            ]);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->excludeList = ['something'];

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->save($modelAbstract);

    }

    public function testDeleteMethodMakesQuery()
    {
        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['delete'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('delete')
            ->with(['id' => 1]);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->delete(1);
    }

    public function testDeleteWithParamsMethodMakesQuery()
    {
        $tableGateWay = $this->getMockBuilder(TableGateway::class)
            ->disableOriginalConstructor()
            ->setMethods(['delete'])
            ->getMock();

        $tableGateWay->expects($this->once())
            ->method('delete')
            ->with(['id' => 1]);

        $mapperAbstract = $this->getMockBuilder(MapperAbstract::class)
            ->getMockForAbstractClass();

        $mapperAbstract->setTableGateway($tableGateWay);

        $mapperAbstract->deleteByParams(['id' => 1]);
    }
}
