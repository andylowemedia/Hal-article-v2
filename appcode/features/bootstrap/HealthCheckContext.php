<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class HealthCheckContext implements Context
{
    private $response;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the health check page is accessed
     */
    public function loadHealthCheck()
    {
        $client = new \GuzzleHttp\Client();
        $this->response = $client->get('hal-article-web/health-check');
    }

    /**
     * @Then a :statusCode health check response will be received
     */
    public function checkStatusCode($statusCode)
    {
        Assert::assertEquals($statusCode, $this->response->getStatusCode());
    }

    /**
     * @Then JSON health check response received
     */
    public function checkJsonResponse()
    {
        Assert::assertEquals(
            json_encode([
                'success' => true,
                'message' => 'Health check success'
            ]),
            $this->response->getBody()->getContents()
        );
    }
}
