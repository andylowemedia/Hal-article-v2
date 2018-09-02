<?php

namespace AppTest\Model;

use App\Model\ArticleCategory;
use PHPUnit\Framework\TestCase;

class ArticleCategoryTest extends TestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = new ArticleCategory;
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
