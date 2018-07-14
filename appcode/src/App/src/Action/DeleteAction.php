<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;


class DeleteAction implements ServerMiddlewareInterface
{
    public function __construct()
    {
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : JsonResponse
    {
        $data = \json_decode($request->getBody()->getContents(), true);
        var_dump($data);
        die();
        return new JsonResponse($data);
    }
    
}
