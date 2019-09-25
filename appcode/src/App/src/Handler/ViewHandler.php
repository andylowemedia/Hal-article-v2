<?php

namespace App\Handler;

use Elasticsearch\ClientBuilder;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class ViewHandler implements RequestHandlerInterface
{
    private $hosts;

    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $requestParams = $request->getQueryParams();

        if (!isset($requestParams['index']) ||
            !isset($requestParams['type']) ||
            (!isset($requestParams['slug']) && !isset($requestParams['id']))) {
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

        $results = $client->search($params);

        $result = current($results['hits']['hits']);

        if (empty($result)) {
            throw new \InvalidArgumentException('Article Not Found', 404);
        }

        $article = $result['_source'];

        $article['id'] = $result['_id'];




        return new JsonResponse([
            'article' => $article
        ]);
    }
}
