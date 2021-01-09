<?php
namespace App\ResultSet;

use Laminas\Db\ResultSet\ResultSet;
use ArrayObject;
use ArrayIterator;

abstract class ResultSetAbstract extends ResultSet
{
    public function elasticsearchInitialize($dataSource)
    {
        if (!is_array($dataSource)) {
            throw new \InvalidArgumentException('Datasource must be an array for Elasticsearch');
        }
        // its safe to get numbers from an array
        $first = current($dataSource['hits']);
        reset($dataSource['hits']);

        $this->fieldCount = $first === false ? 0 : count($first);
        $this->dataSource = new ArrayIterator($dataSource['hits']);

        $this->count = $dataSource['total'];
        $this->buffer = -1; // array's are a natural buffer

        return $this;
    }

    public function current()
    {
        $data = $this->dataSource->current();
        if (empty($data)) {
            return null;
        }

        /** @var $ao ArrayObject */
        $ao = clone $this->arrayObjectPrototype;
        if ($ao instanceof ArrayObject || method_exists($ao, 'exchangeArray')) {
            $dataSource = $data;
            if (isset($data['_source'])) {
                $dataSource = $data['_source'];
                $dataSource['id'] = (int) $data['_id'];
            }
            if (is_array($dataSource)) {
                $ao->exchangeArray($dataSource);
            }
        }

        return $ao;
    }
}
