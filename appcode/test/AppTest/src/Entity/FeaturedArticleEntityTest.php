<?php

declare(strict_types=1);

namespace AppTest\Entity;

use App\Entity\FeaturedArticleEntity;
use PHPUnit\Framework\TestCase;

class FeaturedArticleEntityTest extends TestCase
{
    private $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new FeaturedArticleEntity;
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
