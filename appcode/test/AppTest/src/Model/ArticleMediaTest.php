<?php

namespace AppTest\Model;

use App\Model\ArticleMedia;
use PHPUnit\Framework\TestCase;

class ArticleMediaTest extends TestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = new ArticleMedia;
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

    public function testSetAndGetCodeMethodsWork()
    {
        $this->model->code = '<iframe></iframe>';
        $this->assertEquals('<iframe></iframe>', $this->model->code);
    }

    public function testSetAndGetStatusIdMethodsWork()
    {
        $this->model->statusId = 1;
        $this->assertEquals(1, $this->model->statusId);
    }
}
