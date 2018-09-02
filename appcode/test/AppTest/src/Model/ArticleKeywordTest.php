<?php

namespace AppTest\Model;

use App\Model\ArticleKeyword;
use PHPUnit\Framework\TestCase;

class ArticleKeywordTest extends TestCase
{
    private $model;

    public function setUp()
    {
        parent::setUp();
        $this->model = new ArticleKeyword;
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
