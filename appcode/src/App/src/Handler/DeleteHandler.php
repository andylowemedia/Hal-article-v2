<?php

namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class DeleteHandler implements RequestHandlerInterface
{
    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request) : JsonResponse
    {
        $data = \json_decode($request->getBody()->getContents(), true);
        var_dump($data);
        die();
        return new JsonResponse($data);
    }
}
