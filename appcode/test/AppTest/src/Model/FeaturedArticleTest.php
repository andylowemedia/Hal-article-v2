<?php

namespace AppTest\Model;


use App\Model\FeaturedArticle;
use PHPUnit\Framework\TestCase;

class FeaturedArticleTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new FeaturedArticle;
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

    public function testSetAndGetSiteIdMethodsWork()
    {
        $this->model->siteId = 1;
        $this->assertEquals(1, $this->model->siteId);
    }

    public function testSetAndGetOrderNoMethodsWork()
    {
        $this->model->orderNo = 1;
        $this->assertEquals(1, $this->model->orderNo);
    }


}
