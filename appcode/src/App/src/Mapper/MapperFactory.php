<?php
namespace App\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

use Zend\ServiceManager\ServiceLocatorInterface;

class MapperFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        echo "<pre>";
        echo "CanCreate: {$requestedName}\n";
        echo "</pre>";
    }
    
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        echo "<pre>";
        echo "CanCreateServiceWithName: {$requestedName}\n";
        echo "</pre>";
    }
    
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        echo "<pre>";
        echo "CanCreateServiceWithName: {$requestedName}\n";
        echo "</pre>";
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mapperClass = $requestedName;
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