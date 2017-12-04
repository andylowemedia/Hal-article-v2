<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class SummaryAction implements ServerMiddlewareInterface
{
    private $hosts;
    
    public function __construct(array $hosts)
    {
        $this->hosts = $hosts;
    }
    
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P3M'));
        
        
        $params = [
            'index' => 'articles',
            'type'  => 'article',
            'size'  => 65,
            'date-fr' => $date->format('c'),
            'sort' => 'publishDate:desc',
            'exists' => ['image'],
            'filter' => ['featured' => '1']
        ];
        
        $search = new \App\Query\Search($this->hosts);
        $featured = $search->buildClient()->fetch($params);
        
        $slugs = [];
        
        foreach ($featured as $article) {
            $slugs[] = $article['slug'];
        }
        
        $params['size'] = 99;
        unset($params['exists']); // = ['featured'];
        unset($params['filter']);
        $params['excludes'] = ['slug' => $slugs];
        
        $latest = $search->fetch($params);
        
        return new JsonResponse([
            'total' => 0,
            'articles' => [
                'featured' => $featured,
                'latest' => $latest
            ]
        ]);
    }
}
