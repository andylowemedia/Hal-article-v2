<?php

namespace App\Handler;

use Elasticsearch\ClientBuilder;

use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class ViewHandler implements RequestHandlerInterface
{
    private string $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $slug = $request->getAttribute('slug');
        $params = [
            'index' => 'articles',
            'type' => 'article',

            'body' => [
                'query' => [
                    'term' => [
                        'slug' => $slug
                    ]
                ]
            ]
        ];

        $client = ClientBuilder::create()
                ->setHosts([$this->host])
                ->build();

        $results = $client->search($params);

        $result = current($results['hits']['hits']);

        if (empty($result)) {
            return new EmptyResponse(404);
        }

        $article = $result['_source'];
        $article['id'] = $result['_id'];

        return new JsonResponse(
            [
                'article' => $article
            ]
        );
    }
}
