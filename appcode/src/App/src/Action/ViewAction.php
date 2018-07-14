<?php

namespace App\Action;

use Elasticsearch\ClientBuilder;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ViewAction implements ServerMiddlewareInterface
{
    private $hosts;

    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $requestParams = $request->getQueryParams();
        
        if (!isset($requestParams['index']) || !isset($requestParams['type']) || (!isset($requestParams['slug']) && !isset($requestParams['id']))) {
            throw new \InvalidArgumentException('Index, Type & Slug must be set in query parameters to continue');
        }
        
        
        if (isset($requestParams['id'])) {
            $key = '_id';
            $value = $requestParams['id'];
        } else {
            $key = 'slug';
            $value = strtolower($requestParams['slug']);
        }
        
        
        $params = [
            'index' => 'articles',
            'type' => 'article',
                
            'body' => [
                'query' => [
                    "term" => [
                        $key => $value
                    ]
                ]
            ]
        ];
        
        $client = ClientBuilder::create()
                ->setHosts($this->hosts)
                ->build();
        
        $article = current($client->search($params)['hits']['hits'])['_source'];
        
        return new JsonResponse([
            'article' => $article
        ]);
    }
}
