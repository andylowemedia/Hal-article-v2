<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;


class HealthCheckHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        return new JsonResponse([
            'success' => true,
            'message' => 'Health check success'
        ]);
    }
}
