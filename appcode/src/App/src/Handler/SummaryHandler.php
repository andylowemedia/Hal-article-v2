<?php

namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class SummaryHandler implements RequestHandlerInterface
{
    private $hosts;

    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $date = new \DateTime();
        $date->sub(new \DateInterval('P3M'));

        $pageSize = 100;
        $page = isset($queryParams['page']) ? $queryParams['page'] : 1;


        $from = (($page * $pageSize) - $pageSize);

        $params = [
            'index' => 'articles',
            'type'  => 'article',
            'size'  => $pageSize,
//            'date-fr' => $date->format('c'),
            'sort' => 'publishDate:desc',
            'exists' => ['image'],
            'filter' => ['featured' => '1'],
            'page' => $from
        ];

        $search = new \App\Query\Search($this->hosts);
        $articles = $search->buildClient()->fetch($params);

        return new JsonResponse([
            'total' => $articles['total'],
            'count' => count($articles['results']),
            'articles' => [
                'featured' => $articles['results'],
            ]
        ]);
    }
}
