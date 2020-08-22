<?php

namespace App\Handler;

use App\Query\Search;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

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

        $response = $this->search->fetch($params);

        $count = count($response['results']);

        if ($count === 0) {
            return new EmptyResponse(404);
        }

        return new JsonResponse([
            'total' => $response['total'],
            'count' => count($response['results']),
            'articles' => $response['results']
        ]);
    }
}
