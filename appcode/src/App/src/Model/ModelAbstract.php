<?php
declare(strict_types=1);
namespace App\Model;

use App\ResultSet\ResultSetAbstract;

/**
 * Class ModelAbstract
 * @package App\Model
 */
abstract class ModelAbstract
{
    public function __construct(array $data = null)
    {
        if ($data) {
            $this->exchangeArray($data);
        }
    }

    public function __set($name, $value)
    {
        //$method = 'set' . ucfirst($name);
        $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        if (('mapper' === $name) || !method_exists($this, $method)) {
            throw new ModelException('Invalid page property :' . $method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (('mapper' === $name) || !method_exists($this, $method)) {
            throw new ModelException('Invalid page property ' . $method);
        }
        return $this->$method();
    }


    public function exchangeArray(array $options)
    {

        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($this->removeUnderScore($key));
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    protected function removeUnderScore($column)
    {
        // preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $text)
        $colArray = explode("_", $column);

        $property = null;
        $numWords = count($colArray);
        for ($n=0; $n<$numWords; $n++) {
            if ($n > 0) {
                $property .= ucwords($colArray[$n]);
            } else {
                $property .= $colArray[$n];
            }
        }
        return $property;
    }

//    protected function convertToUnderScore(array $data)
//    {
//        $newData = [];
//        foreach ($data as $key => $value) {
//            $newKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
//            $newData[$newKey] = $value;
//        }
//
//        return $newData;
//    }

    public function toArray()
    {
        $data = [];
        foreach ($this as $key => $property) {
            if ($property instanceof ModelAbstract || $property instanceof ResultSetAbstract) {
                if ($property instanceof ResultSetAbstract) {
                    $property->buffer();
                }
                $data[$key] = $property->toArray();
            } else {
                $method = 'get' . ucfirst($this->removeUnderScore($key));
                if (method_exists($this, $method)) {
                    $data[$key] = $this->$method();
                }
            }
        }
        return $data;
    }

    public function toArraySql()
    {
        $data = [];
        foreach ($this as $key => $property) {
            $newKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
            if ($property instanceof ModelAbstract) {
                $data[$newKey] = $property->toArraySql();
            } else {
                $method = 'get' . ucfirst($key);
                if (method_exists($this, $method)) {
                    $data[$newKey] = $this->$method();
                }
            }
        }
        return $data;
    }
}
