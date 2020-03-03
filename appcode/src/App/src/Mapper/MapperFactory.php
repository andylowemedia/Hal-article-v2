<?php
namespace App\Mapper;

use Laminas\Db\TableGateway\TableGateway;
use Psr\Container\ContainerInterface;

class MapperFactory
{
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): MapperAbstract {
        $mapperClass = $requestedName;
        $entityClass = str_replace('Mapper', 'Entity', $mapperClass);
        $resultSetClass = str_replace('Mapper', 'ResultSet', $mapperClass);

        $resultSet = new $resultSetClass;
        $resultSet->setArrayObjectPrototype(new $entityClass());

        $adapter = $container->get('ArticlesDbAdapter');

        $mapper = new $mapperClass;

        $tableGateway = new TableGateway($mapper->getTableName(), $adapter, null, $resultSet);

        $mapper->setTableGateway($tableGateway);

        return $mapper;
    }
}
