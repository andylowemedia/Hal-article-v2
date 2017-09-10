<?php
namespace App\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Interop\Container\ContainerInterface;

class MapperFactory
{
    public function __invoke(ContainerInterface $container, $mapperClass)
    {
        $modelClass = str_replace('Mapper', 'Model', $mapperClass);
        $resultSetClass = str_replace('Mapper', 'ResultSet', $mapperClass);
        
        $resultSet = new $resultSetClass;
        $resultSet->setArrayObjectPrototype(new $modelClass());
        
        $adapter = $container->get('ArticlesDbAdapter');
        
        $mapper = new $mapperClass;
        
        $tableGateway = new TableGateway($mapper->getTableName(), $adapter, null, $resultSet);
        
        $mapper->setTableGateway($tableGateway);
        
        return $mapper;
    }
}