<?php

namespace App\Handler;

use App\Query\Search;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class SearchHandler implements RequestHandlerInterface
{
    private $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $params = $request->getQueryParams();

        $size = 100;
        $page = isset($params['page']) ? ($params['page'] - 1) : 0;

        $params['page'] = ($page * $size);




        $response = $this->search->fetch($params);

        return new JsonResponse([
            'total' => 0, // $response['totalCount'],
            'count' => 0, // $response['count'],
            'articles' => $response
        ]);
    }
}
