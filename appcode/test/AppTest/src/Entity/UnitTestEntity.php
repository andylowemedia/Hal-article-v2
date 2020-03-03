<?php

namespace AppTest\Entity;

use App\Entity\EntityAbstract;
use App\ResultSet\ResultSetAbstract;

class UnitTestEntity extends EntityAbstract
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

    public function setSomething(EntityAbstract $something): self
    {
        $this->something = $something;
        return $this;
    }

    public function getSomething(): ?EntityAbstract
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
