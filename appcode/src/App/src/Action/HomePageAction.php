<?php

namespace App\Action;

use Elasticsearch\ClientBuilder;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class HomePageAction implements ServerMiddlewareInterface
{
    private $hosts;

    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $requestParams = $request->getQueryParams();
        
        if (!isset($requestParams['index']) || !isset($requestParams['type']) || !isset($requestParams['slug'])) {
            throw new \InvalidArgumentException('Index, Type & Slug must be set in query parameters to continue');
        }
        
        $params = [
            'index' => $requestParams['index'],
            'type' => $requestParams['type'],
                
            'body' => [
                'track_scores' => true,
                "min_score" => 1,
                'query' => [
                    "bool" => [
                        'must' => [
                            "match_phrase" => [
                                'slug' => $requestParams['slug']
                            ]
                        ]
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
