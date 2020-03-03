<?php
namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;


/**
 * @OA\Server(url="http://gateway.low-emedia.com/article/", description="public production network")
 * @OA\Server(url="http://article.low-emedia.com/", description="restricted production network")
 * @OA\Server(url="http://hal-article-v2.discovery:8082/", description="local development network")
 * @OA\Info(title="Hal ArticleMapper Microservice", version="1.0.0")
 */
class HealthCheckHandler implements RequestHandlerInterface
{
    /**
     * @OA\Get(
     *     path="/health-check",
     *     @OA\Response(response="200", description="Health Check")
     * )
     */
    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
//        sleep(20);

        return new JsonResponse([
            'success' => true,
            'message' => 'Article Microservice is running correctly'
        ]);
    }
}
