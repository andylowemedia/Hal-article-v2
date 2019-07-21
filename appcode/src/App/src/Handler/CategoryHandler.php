<?php

namespace App\Handler;

use App\Query\Search;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CategoryHandler implements RequestHandlerInterface
{
    private $apiConfig = [];
    private $searchClient;
    private $response = [];
    private $topLevel = false;

    public function __construct(array $config, Search $search)
    {
        $this->apiConfig = $config;
        $this->search = $search;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $params = $request->getQueryParams();
        $this->topLevel = false;

        if (isset($params['top-level'])) {
            $this->topLevel = $params['top-level'];
        }

        $size = 25;
        if (isset($params['size'])) {
            $size = $params['size'];
        }

        $this->response['count'] = 0;
        $this->response['totalCount'] = 0;

        $client = new \GuzzleHttp\Client();
        $res = $client->request(
            'GET',
            $this->apiConfig['category'] . '/category/code/' . $request->getAttribute('slug')
        );

        $data = \json_decode($res->getBody()->getContents());

        if ((!isset($params['group']) || $params['group'] !== 'true')
                && $this->topLevel === false
                && isset($data->category->childCategories)
                && count($data->category->childCategories) > 0) {
            $this->response['category']['name'] = $data->category->name;
            foreach ($data->category->childCategories as $category) {
                $this->processCategory($request, $category, $size);
            }
        } else {
            $this->processCategory($request, $data->category, $size);
        }

        return new JsonResponse($this->response);
    }

    private function processCategory($request, $category, $size)
    {
        $params = $request->getQueryParams();

        $search = $this->search;


        $page = isset($params['page']) ? $params['page'] : 1;
        $from = (($page * $size) - $size);

        $params['sort'] = ['publishDate:desc'];
        $params['size'] = $size;
        $params['category'] = [$category->code];
        if (isset($params['image-only'])) {
            $params['exists'] = ['image'];
        }
        $params['page'] = $from;

        $data = $search->fetch($params);

        $results = [];

        $results['source'] = $data['results'];

        $results['code'] = $category->code;

        $results['name'] = '';
        if (isset($category->parentCategory)) {
            $results['name'] .= $category->parentCategory->name . ' - ';
        }
        $results['name'] .= $category->name;

        $this->response['articles'][$category->code] = $results;
//        $this->response['count'] += (int) $results['count'];
//        $this->response['totalCount'] += (int) $results['totalCount'];

        return $this;
    }
}
