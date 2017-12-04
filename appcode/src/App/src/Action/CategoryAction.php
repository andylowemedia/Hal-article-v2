<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class CategoryAction implements ServerMiddlewareInterface
{
    private $apiConfig = [];
    private $hosts = [];
    private $response = [];
    private $topLevel = false;
    
    public function __construct(array $config, array $hosts)
    {
        $this->apiConfig = $config;
        $this->hosts = $hosts;
    }
    
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
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
        $res = $client->request('GET', $this->apiConfig['category'] . '/category/code/' . $request->getAttribute('slug'));
        
        $data = json_decode($res->getBody());
        
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
        
        $search = new \App\Query\Search($this->hosts);
        
        $params['sort'] = ['publishDate:desc'];
        $params['page'] = 1;
        $params['size'] = $size;
        $params['category'] = [$category->code];
        if (isset($params['image-only'])) {
            $params['exists'] = ['image'];
        }
        $results = [];
        
        $results['source'] = $search->buildClient()->fetch($params);
        
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
