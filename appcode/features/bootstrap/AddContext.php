<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines application features from the specific context.
 */
class AddContext implements Context
{
    /**
     * @var array $articleData
     */
    private array $articleData;

    /**
     * @var ResponseInterface $response
     */
    private ResponseInterface $response;

    /**
     * @Given article data is prepared
     */
    public function articleDataIsPrepared()
    {
        $this->articleData = [
            'title'             => 'Test Article',
            'subtitle'          => 'Test subtitle',
            'summary'           => 'Summary or first paragraph of the article',
            'content'           => '<p>This is the article text, and can be very big anf as long as it is needed</p>',
            'author'            => 'Whomever the Author is',
            'sourceId'          => 1,
            'url'               => 'https://www.example.com/test-article',
            'articleTypeId'     => 1,
            'publishDate'       => '2020-01-01 12:00:00',
            'images'            => ['https://www.example.com/image.jpg'],
            'media'             => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/soemthing" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'],
            'categoryIds'       => [2, 3],
            'categoryCodes'     => ['politics', 'business'],
            'displayCategories' => [["code" => "politics", "name" => "Politics"], ["code" => "business", "name" => "Business"]],
            'keywords'          => ['something'],
            'headline'          => true,
        ];
    }

    /**
     * @When save article post request has been sent
     */
    public function saveArticlePostRequestHasBeenSent()
    {
        $this->response = (new Client())->post('hal-article-web', ['body' => \json_encode($this->articleData)]);
    }

    /**
     * @Then a :statusCode response is received
     */
    public function aResponseIsReceived(int $statusCode)
    {
        Assert::assertEquals($statusCode, $this->response->getStatusCode());
    }

    /**
     * @Then a successfully created message is received with an article ID
     */
    public function aSuccessfullyCreatedMessageIsReceivedWithAnArticleId()
    {
        $expectedResponse = <<<JSON
{"success":true,"message":"Article added","article":{"id":1429688,"slug":"test-article","title":"Test Article","subtitle":"Test subtitle","summary":"This is the article text, and can be very big anf as long as it is needed","content":"\u003Cp\u003EThis is the article text, and can be very big anf as long as it is needed\u003C/p\u003E","author":"Whomever the Author is","originalUrl":"https://www.example.com/test-article","sourceId":1,"articleTypeId":1,"publishDate":"2020-01-01 12:00:00","statusId":2,"images":[{"id":2934542,"articleId":1429688,"url":"https://www.example.com/image.jpg","statusId":2}],"media":[{"id":35138,"articleId":1429688,"code":"\u003Ciframe width=\u0022560\u0022 height=\u0022315\u0022 src=\u0022https://www.youtube.com/embed/soemthing\u0022 frameborder=\u00220\u0022 allow=\u0022accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\u0022 allowfullscreen\u003E\u003C/iframe\u003E","statusId":2}],"categoryIds":[{"id":620686,"articleId":1429688,"categoryId":2},{"id":620687,"articleId":1429688,"categoryId":3}],"categoryCodes":["politics","business"],"displayCategories":[{"code":"politics","name":"Politics"},{"code":"business","name":"Business"}],"keywords":[{"id":3314392,"articleId":1429688,"keyword":"something"}],"featured":[{"id":471213,"articleId":1429688,"siteId":3,"orderNo":0}]}}
JSON;
        Assert::assertEquals(json_decode($expectedResponse), json_decode($this->response->getBody()->getContents()));
    }
}
