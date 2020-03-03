<?php

namespace AppTest\Entity;

use App\Entity\ArticleMediaEntity;
use PHPUnit\Framework\TestCase;

class ArticleMediaEntityTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new ArticleMediaEntity;
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
