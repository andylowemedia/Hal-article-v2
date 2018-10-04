<?php

namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Query\Summary;

class SummaryHandler implements RequestHandlerInterface
{
    private $summary;

    public function __construct(Summary $summary)
    {
        $this->summary = $summary;
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

        $articles = $this->summary->fetch($params);

        return new JsonResponse([
            'total' => 0,
            'count' => count($articles),
            'articles' => [
                'featured' => $articles,
            ]
        ]);
    }
}
