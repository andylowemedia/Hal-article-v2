<?php

namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class SearchHandler implements RequestHandlerInterface
{
    private $hosts;

    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $params = $request->getQueryParams();

        $search = new \App\Query\Search($this->hosts);

        $size = 100;
        $page = isset($params['page']) ? ($params['page'] - 1) : 0;

        $params['page'] = ($page * $size);

        $response = $search->buildClient()->fetch($params);

        return new JsonResponse([
            'total' => 0, // $response['totalCount'],
            'count' => 0, // $response['count'],
            'articles' => $response
        ]);
    }
}