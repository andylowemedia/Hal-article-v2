<?php

declare(strict_types=1);

namespace AppTest\Entity;


use App\Entity\ArticleImageEntity;
use PHPUnit\Framework\TestCase;

class ArticleImageEntityTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new ArticleImageEntity;
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
