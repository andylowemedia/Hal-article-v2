<?php

namespace AppTest\Model;

use App\Model\DisplayArticle;
use PHPUnit\Framework\TestCase;

class DisplayArticleTest extends TestCase
{
    private $article;

    public function setUp(): void
    {
        parent::setUp();

        $this->article = new DisplayArticle();
    }

    public function testSetAndGetSlugMethodsWork()
    {
        $this->article->slug = 'soemthing';
        $this->assertEquals('soemthing', $this->article->slug);
    }

    public function testSetAndGetTitleMethodsWork()
    {
        $this->article->title = 'soemthing else';

        $this->assertEquals('soemthing else', $this->article->title);
    }

    public function testSetAndGetSubtitleMethodsWork()
    {
        $this->article->subtitle = 'soemthing else';

        $this->assertEquals('soemthing else', $this->article->subtitle);
    }

    public function testSetAndGetSummaryMethodsWork()
    {
        $this->article->summary = 'soemthing else';

        $this->assertEquals('soemthing else', $this->article->summary);
    }


    public function testSetAndGetAuthorMethodsWork()
    {
        $this->article->author = 'soemthing';

        $this->assertEquals('soemthing', $this->article->author);
    }

    public function testSetAndGetUrlMethodsWork()
    {
        $this->article->url = 'http://www.google.com';

        $this->assertEquals('http://www.google.com', $this->article->url);
    }

    public function testSetAndGetSourceMethodsWork()
    {
        $this->article->source = 'BBC';

        $this->assertEquals('BBC', $this->article->source);
    }

    public function testSetAndGetPublishDateMethodsWork()
    {
        $date = date('Y-m-d H:i:s');

        $this->article->publishDate = $date;

        $this->assertEquals($date, $this->article->getPublishDate('Y-m-d H:i:s'));
    }

    public function testSetAndGetImageMethodsWork()
    {
        $this->article->image = 'something.jpg';

        $this->assertEquals('something.jpg', $this->article->image);
    }

    public function testSetAndGetDisplayCategoriesMethodsWork()
    {
        $this->article->displayCategories = ['entertainment'];

        $this->assertEquals(['entertainment'], $this->article->displayCategories);
    }

    public function testSetAndGetKeywordsMethodsWork()
    {
        $this->article->keywords = ['entertainment'];

        $this->assertEquals(['entertainment'], $this->article->keywords);
    }

}
