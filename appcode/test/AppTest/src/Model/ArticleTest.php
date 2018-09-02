<?php

namespace AppTest\Model;

use App\Model\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    private $article;

    public function setUp()
    {
        parent::setUp();

        $this->article = new Article();
    }

    public function testSetAndGetIdMethodsWorks()
    {
        $this->article->id = 1;
        $this->assertEquals(1, $this->article->id);
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

    public function testCreateSlugWorks()
    {
        $this->article->title = 'soemthing else';
        $this->article->createSlug();
        $this->assertEquals('soemthing-else', $this->article->slug);
    }

    /**
     * @expectedException \App\Model\ModelException
     */
    public function testCreateSlugThrowsException()
    {
        $this->article->title = '';
        $this->article->createSlug();
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

    public function testSetAndGetContentMethodsWork()
    {
        $this->article->content = 'soemthing else';

        $this->assertEquals('soemthing else', $this->article->content);
    }

    public function testCreateSummaryBasedOnContentWorks()
    {
        $this->article->content = 'soemthing else';

        $this->article->createSummary();

        $this->assertEquals('soemthing else', $this->article->summary);
    }

    /**
     * @expectedException \App\Model\ModelException
     */
    public function testCreateSummaryThrowsExceptionWhenNoContentIsSet()
    {
        $this->article->content = '';

        $this->article->createSummary();
    }

    public function testSetAndGetAuthorMethodsWork()
    {
        $this->article->author = 'soemthing';

        $this->assertEquals('soemthing', $this->article->author);
    }

    public function testSettingDefaultAuthor()
    {
        $this->article->defaultAuthor();

        $this->assertEquals('Unknown', $this->article->author);
    }

    public function testSetAndGetOriginalUrlMethodsWork()
    {
        $this->article->originalUrl = 'http://www.google.com';

        $this->assertEquals('http://www.google.com', $this->article->originalUrl);
    }

    public function testSetAndGetSourceIdMethodsWork()
    {
        $this->article->sourceId = 1;

        $this->assertEquals(1, $this->article->sourceId);
    }

    public function testSetAndGetArticleTypeIdMethodsWork()
    {
        $this->article->articleTypeId = 1;

        $this->assertEquals(1, $this->article->articleTypeId);
    }

    public function testSetAndGetPublishDateMethodsWork()
    {
        $date = date('Y-m-d H:i:s');

        $this->article->publishDate = $date;

        $this->assertEquals($date, $this->article->getPublishDate('Y-m-d H:i:s'));
    }

    public function testSetAndGetDateMethodsWork()
    {
        $date = date('Y-m-d H:i:s');

        $this->article->date = $date;

        $this->assertEquals($date, $this->article->getDate('Y-m-d H:i:s'));
    }

    public function testGetDateMethodSetsNowAsDefaultAndReturnsAValidDate()
    {

        $date = $this->article->getDate('Y-m-d H:i:s');

        $dateTime = new \DateTime($date);

        $this->assertSame($dateTime->format('Y-m-d H:i:s'), $date);
    }

    public function testSetAndGetStatusIdMethodsWork()
    {
        $this->article->statusId = 1;

        $this->assertEquals(1, $this->article->statusId);
    }

    public function testToArrayReturnsAnArrayBasedOnObject()
    {
        $date = date('Y-m-d H:i:s');

        $this->article->id = 1;
        $this->article->title = 'soemthing else';
        $this->article->subtitle = 'soemthing else';
        $this->article->summary = 'soemthing else';
        $this->article->content = 'soemthing else';
        $this->article->author = 'soemthing';
        $this->article->originalUrl = 'http://www.google.com';
        $this->article->sourceId = 1;
        $this->article->articleTypeId = 1;
        $this->article->publishDate = $date;
        $this->article->date = $date;
        $this->article->statusId = 1;

        $expectedData = [
            'id'            => 1,
            'title'         => 'soemthing else',
            'slug'          => 'soemthing-else',
            'subtitle'      => 'soemthing else',
            'summary'       => 'soemthing else',
            'content'       => 'soemthing else',
            'author'        => 'soemthing',
            'originalUrl'   => 'http://www.google.com',
            'sourceId'      => 1,
            'articleTypeId' => 1,
            'publishDate'   => $date,
            'date'          => $date,
            'statusId'      => 1
        ];

        $this->assertEquals($expectedData, $this->article->toArray());
    }

    public function testToArraySqlReturnsAnArrayBasedOnObject()
    {
        $date = date('Y-m-d H:i:s');

        $this->article->id = 1;
        $this->article->title = 'soemthing else';
        $this->article->subtitle = 'soemthing else';
        $this->article->summary = 'soemthing else';
        $this->article->content = 'soemthing else';
        $this->article->author = 'soemthing';
        $this->article->originalUrl = 'http://www.google.com';
        $this->article->sourceId = 1;
        $this->article->articleTypeId = 1;
        $this->article->publishDate = $date;
        $this->article->date = $date;
        $this->article->statusId = 1;

        $expectedData = [
            'id'                => 1,
            'title'             => 'soemthing else',
            'slug'              => 'soemthing-else',
            'subtitle'          => 'soemthing else',
            'summary'           => 'soemthing else',
            'content'           => 'soemthing else',
            'author'            => 'soemthing',
            'original_url'      => 'http://www.google.com',
            'source_id'          => 1,
            'article_type_id'   => 1,
            'publish_date'      => $date,
            'date'              => $date,
            'status_id'         => 1
        ];

        $this->assertEquals($expectedData, $this->article->toArraySql());
    }

}
