<?php

namespace App\Model;

class Category extends ModelAbstract
{

    protected $id = null;

    protected $fullPath = null;

    protected $parentId = null;

    protected $baseLevel = null;

    protected $code = null;

    protected $name = null;

    protected $statusId = null;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setBaseLevel($baseLevel)
    {
        $this->baseLevel = $baseLevel;
        return $this;
    }

    public function getBaseLevel()
    {
        return $this->baseLevel;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function getStatusId()
    {
        return $this->statusId;
    }


}

