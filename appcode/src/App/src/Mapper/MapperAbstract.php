<?php
namespace App\Mapper;

use App\Model\ModelAbstract;

use Zend\Db\TableGateway\TableGateway;

/**
 * Description of MapperAbstract
 *
 * @author andylowe
 */
abstract class MapperAbstract
{
    //protected $excludeList = array();
    protected $tableGateway;
    
    public function __construct()
    {
        if (!isset($this->excludeList) || !is_array($this->excludeList)) {
            throw new MapperException("Exclude list must be set as an array");
        }
    }
    
    public function setTableGateway(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }
    
    public function getTableGateway()
    {
        return $this->tableGateway;
    }
    
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }
    
    public function getTableName()
    {
        return $this->tableName;
    }
    
    public function count($where = null, $group = null)
    {
        $sql = $this->tableGateway->getSql();
        if ($where instanceof \Zend\Db\Sql\Select) {
            $select = $where;
        } else {
            $select = $sql->select()->columns(array('count'=>new Sql\Expression('count(id)')));
            if ($where) {
                $select->where($where);
            }
            if ($group) {
                $select->group($group);
            }
        }
        $statement = $sql->prepareStatementForSqlObject($select);
        $results   = $statement->execute()->current();
        if (!isset($results['count'])) {
            throw new MapperException("Results must contain 'count' field");
        }
        return $results['count'];
    }

    public function fetchAll($where = null, $order = null, $limit = null, $offset = 0)
    {
        if ($where instanceof \Zend\Db\Sql\Select) {
            $select = $where;
        } else {
            $select = $this->tableGateway->getSql()->select();
            if ($where) {
                $select->where($where);
            }
            if ($order) {
                $select->order($order);
            }
            if ($limit) {
                $select->limit($limit);
                $select->offset($offset);
            }
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function find($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        return $row;
    }
    
    public function findByParams($params, $order = null)
    {
        if ($params instanceof \Zend\Db\Sql\Select) {
            $select = $params;
        } else {
            $select = $this->tableGateway->getSql()->select();
            $select->where($params);

            if ($order) {
                $select->order($order);
            }
        }
        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        return $row;
    }

    public function save(ModelAbstract $model)
    {
        $data = $this->changeData($model->toArray());

        $id = (int)$model->id;
        if ($id == 0) {
            unset($data['id']);
            $this->tableGateway->insert($data);
            $result = $this->tableGateway->getLastInsertValue();
            $model->id = $result;
        } else {
            if ($this->find($id)) {
                $result = $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new MapperException('ID does not exist');
            }
        }
        return $result;
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => $id));
        return $this;
    }
    
    public function deleteByParams(array $where)
    {
        $this->tableGateway->delete($where);
        return $this;
    }
    
    protected function changeData(array $data)
    {
        $newData = array();
        foreach ($data as $key => $value) {
            if ($this->checkInExcludeList($key)) {
                continue;
            }
            $newKey = $this->convertToUnderScore($key);
            $newData[$newKey] = $value;
        }

        return $newData;
    }

    protected function checkInExcludeList($key)
    {
        return in_array($key, $this->excludeList);
    }

    protected function convertToUnderScore($key)
    {
        $newKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
        return $newKey;
    }
}