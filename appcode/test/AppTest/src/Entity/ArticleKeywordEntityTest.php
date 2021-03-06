<?php

declare(strict_types=1);

namespace AppTest\Entity;

use App\Entity\ArticleKeywordEntity;
use PHPUnit\Framework\TestCase;

class ArticleKeywordEntityTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();
        $this->model = new ArticleKeywordEntity;
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

    public function testSetAndGetKeywordMethodsWork()
    {
        $this->model->keyword = 'something else';
        $this->assertEquals('something else', $this->model->keyword);
    }

}
