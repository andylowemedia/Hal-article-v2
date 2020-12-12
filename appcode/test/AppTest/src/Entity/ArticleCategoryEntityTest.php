<?php

declare(strict_types=1);

namespace AppTest\Entity;

use App\Entity\ArticleCategoryEntity;
use PHPUnit\Framework\TestCase;

class ArticleCategoryEntityTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new ArticleCategoryEntity;
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

    public function testSetAndGetCategoryIdMethodsWork()
    {
        $this->model->categoryId = 1;
        $this->assertEquals(1, $this->model->categoryId);
    }
}
