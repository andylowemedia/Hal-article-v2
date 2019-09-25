<?php
namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class HealthCheckHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
//        sleep(20);

        return new JsonResponse([
            'success' => true,
            'message' => 'Article Microservice is running correctly'
        ]);
    }
}
