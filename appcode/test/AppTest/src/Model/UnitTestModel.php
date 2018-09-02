<?php

namespace AppTest\Model;

use App\Model\ModelAbstract;
use App\ResultSet\ResultSetAbstract;

class UnitTestModel extends ModelAbstract
{
    protected $id;

    protected $rows;

    protected $something;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setSomething(ModelAbstract $something): self
    {
        $this->something = $something;
        return $this;
    }

    public function getSomething(): ?ModelAbstract
    {
        return $this->something;
    }

    public function setRows(ResultSetAbstract $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    public function getRows(): ?ResultSetAbstract
    {
        return $this->rows;
    }

}
