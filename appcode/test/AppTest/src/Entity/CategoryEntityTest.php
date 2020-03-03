<?php

declare(strict_types=1);

namespace AppTest\Entity;

use App\Entity\CategoryEntity;
use PHPUnit\Framework\TestCase;

class CategoryEntityTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new CategoryEntity;
    }

    public function testSetAndGetIdMethodsWork()
    {
        $this->model->id = 1;
        $this->assertEquals(1, $this->model->id);
    }

    public function testSetAndGetFullPathMethodsWork()
    {
        $this->model->fullPath = '1.2.1';
        $this->assertEquals('1.2.1', $this->model->fullPath);
    }

    public function testSetAndGetParentIdMethodsWork()
    {
        $this->model->parentId = 1;
        $this->assertEquals(1, $this->model->parentId);
    }

    public function testSetAndGetBaseLevelMethodsWork()
    {
        $this->model->baseLevel = true;
        $this->assertTrue($this->model->baseLevel);
    }

    public function testSetAndGetCodeMethodsWork()
    {
        $this->model->code = 'currentAffair';
        $this->assertEquals('currentAffair', $this->model->code);
    }

    public function testSetAndGetNameMethodsWork()
    {
        $this->model->name = 'current affair';
        $this->assertEquals('current affair', $this->model->name);
    }

    public function testSetAndGetStatusIdMethodsWork()
    {
        $this->model->statusId = 1;
        $this->assertEquals(1, $this->model->statusId);
    }


}
