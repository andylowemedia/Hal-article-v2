<?php

namespace AppTest\Model;


use App\Model\SourceHistory;
use PHPUnit\Framework\TestCase;

class SourceHistoryTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new SourceHistory;
    }

    public function testSetAndGetIdMethodsWork()
    {
        $this->model->id = 1;
        $this->assertEquals(1, $this->model->id);
    }

    public function testSetAndGetSourceIdMethodsWork()
    {
        $this->model->sourceId = 1;
        $this->assertEquals(1, $this->model->sourceId);
    }

    public function testSetAndGetUrlMethodsWork()
    {
        $this->model->url = 'http://www.google.com';
        $this->assertEquals('http://www.google.com', $this->model->url);
    }

    public function testSetAndGetMessageMethodsWork()
    {
        $this->model->message = 'logging some message';
        $this->assertEquals('logging some message', $this->model->message);
    }

    public function testSetAndGetStatusIdMethodsWork()
    {
        $this->model->statusId = 1;
        $this->assertEquals(1, $this->model->statusId);
    }

    public function testSetAndGetDateMethodsWork()
    {
        $date = date("Y-m-d H:i:s");

        $this->model->date = $date;
        $this->assertEquals($date, $this->model->date);
    }
}
