<?php

namespace AppTest\Model;


use App\Model\ArticleImage;
use PHPUnit\Framework\TestCase;

class ArticleImageTest extends TestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = new ArticleImage;
    }

    public function testSetAndGetIdMethodsWork()
    {
        $this->model->id = 1;
        $this->assertEquals(1, $this->model->id);
    }

    public function testSetAndGetArticleIdMethodsWork()
    {
        $this->model->articleId = 1;
        $this->assertEquals(1, $this->model->articleId);
    }

    public function testSetAndGetUrlMethodsWork()
    {
        $this->model->url = 'something.jpg';
        $this->assertEquals('something.jpg', $this->model->url);
    }

    public function testSetAndGetStatusIdMethodsWork()
    {
        $this->model->statusId = 1;
        $this->assertEquals(1, $this->model->statusId);
    }
}
