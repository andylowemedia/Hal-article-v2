<?php

declare(strict_types=1);

namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ArticleAddValidationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = \json_decode($request->getBody()->getContents(), true);

        if (empty($data)) {
            throw new \InvalidArgumentException('Request body is not valid JSON or is empty', 400);
        }

        return $handler->handle($request);
    }
}