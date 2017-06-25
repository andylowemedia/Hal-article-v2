<?php

namespace App\Action;

use Elasticsearch\ClientBuilder;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;

class HomePageAction implements ServerMiddlewareInterface
{
    private $router;

    public function __construct(Router\RouterInterface $router)
    {
        $this->router   = $router;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $requestParams = $request->getQueryParams();
        
        $params = [
            'index' => $params['index'],
            'type' => $params['type'],
                
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
                ->setHosts(['10.211.55.29:9200'])
                ->build();
        
        $article = current($client->search($params)['hits']['hits'])['_source'];
        
        return new JsonResponse([
            'article' => $article
        ]);
    }
}
