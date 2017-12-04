<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class SearchAction implements ServerMiddlewareInterface
{
    private $hosts;
    
    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }
    
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getQueryParams();
        
        $search = new \App\Query\Search($this->hosts);
        
        $response = $search->buildClient()->fetch($params);
        
        return new JsonResponse([
            'total' => 0, // $response['totalCount'],
            'count' => 0, // $response['count'],
            'articles' => $response
        ]);
    }
}
