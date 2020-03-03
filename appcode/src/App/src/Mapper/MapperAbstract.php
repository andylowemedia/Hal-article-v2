<?php
declare(strict_types=1);
namespace App\Mapper;

use App\Entity\EntityAbstract;

use App\ResultSet\ResultSetAbstract;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql;

/**
 * Description of MapperAbstract
 *
 * @author andylowe
 */
abstract class MapperAbstract
{
    /**
     * @var TableGateway
     */
    private $tableGateway;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * Sets Zend DB Table Gateway
     * @param TableGateway $tableGateway
     * @return MapperAbstract
     */
    public function setTableGateway(TableGateway $tableGateway): self
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }

    /**
     * Gets Zend DB Table Gateway
     * @return TableGateway
     */
    public function getTableGateway(): TableGateway
    {
        return $this->tableGateway;
    }

    /**
     * Sets table name
     * @param string $tableName
     * @return MapperAbstract
     */
    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Gets table name
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Count query method
     * @param null $where
     * @param null $group
     * @return int
     * @throws MapperException
     */
    public function count($where = null, $group = null): int
    {
        $sql = $this->tableGateway->getSql();
        if ($where instanceof Sql\Select) {
            $select = $where;
        } else {
            $select = $sql->select()->columns(['count'=>new Sql\Expression('count(id)')]);
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
        return (int) $results['count'];
    }

    /**
     * Fetch All method for generic queries mapping to the preset data model
     * @param null $where
     * @param null $order
     * @param null $limit
     * @param int $offset
     * @return ResultSetAbstract
     */
    public function fetchAll($where = null, $order = null, $limit = null, $offset = 0): ResultSetAbstract
    {
        if ($where instanceof Sql\Select) {
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

    /**
     * Find individual row and map to preset data model by id
     * @param int $id
     * @return EntityAbstract
     */
    public function find(int $id): ?EntityAbstract
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        return $row;
    }

    /**
     * Find individual row and map to preset data model by params
     * @param $params
     * @param null $order
     * @return EntityAbstract
     */
    public function findByParams($params, $order = null): EntityAbstract
    {
        if ($params instanceof Sql\Select) {
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

    /**
     * @param EntityAbstract $model
     * @return int
     * @throws MapperException
     */
    public function save(EntityAbstract $model): int
    {
        $data = $this->changeData($model->toArray());

        $id = (int) $model->id;
        if ($id == 0) {
            unset($data['id']);
            $this->tableGateway->insert($data);
            $result = (int) $this->tableGateway->getLastInsertValue();
            $model->id = $result;
        } else {
            $result = (int) $this->tableGateway->update($data, ['id' => $id]);
        }
        return $result;
    }

    /**
     * Deletes record by id
     * @param int $id
     * @return MapperAbstract
     */
    public function delete(int $id): self
    {
        $this->tableGateway->delete(['id' => $id]);
        return $this;
    }

    /**
     * Deletes records by parameters
     * @param array $where
     * @return MapperAbstract
     */
    public function deleteByParams(array $where): self
    {
        $this->tableGateway->delete($where);
        return $this;
    }

    /**
     * Run filtering through filtering methods
     * @param array $data
     * @return array
     * @throws MapperException
     */
    protected function changeData(array $data): array
    {
        $newData = [];
        foreach ($data as $key => $value) {
            if ($this->checkInExcludeList($key)) {
                continue;
            }
            $newKey = $this->convertToUnderScore($key);
            $newData[$newKey] = $value;
        }

        return $newData;
    }

    /**
     * Check if key is in exclude list
     * @param string $key
     * @return bool
     * @throws MapperException
     */
    protected function checkInExcludeList(string $key): bool
    {
        if (!isset($this->excludeList) || !is_array($this->excludeList)) {
            throw new MapperException('Exclude list should be set');
        }

        return in_array($key, $this->excludeList);
    }

    /**
     * Converts key from camel case to underscore
     * @param string $key
     * @return string
     */
    protected function convertToUnderScore(string $key): string
    {
        $newKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
        return $newKey;
    }
}
