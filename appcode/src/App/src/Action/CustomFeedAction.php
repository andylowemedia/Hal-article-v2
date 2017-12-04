<?php
namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Elasticsearch\Client as ElasticsearchClient;
use App\ResultSet\Article as ArticleResultSet;
use App\Model\DisplayArticle as ArticleModel;


class CustomFeedAction implements ServerMiddlewareInterface
{
    private $elasticsearchClient;
    
    public function __construct(ElasticsearchClient $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }
    
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $queryParams = $request->getQueryParams();
        
        $params = [
            'index' => 'articles',
            'type' => 'article',
                
            'body' => [
                'size' => $queryParams['size'],
                'from' => $queryParams['from'],
                'min_score' => $queryParams['threshold'],
                'query' => [
                    'bool' => [
                        'filter' => [
                            'range' => [
                                'publishDate' => [
                                    'gte' => $queryParams['start-date']
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        if (isset($queryParams['categories'])) {
            foreach ($queryParams['categories'] as $category) {
                $breakup = explode('---', $category);
                $categoryCode = $breakup[0];
                $score = (int) $breakup[1];

                $params['body']['query']['bool']['should'][] = [
                    'term' => [
                        'categories' => [
                            'value' => $categoryCode,
                            'boost' => $score
                        ]
                    ]
                ];
            }
        }
        
        if (isset($queryParams['keywords'])) {
            foreach ($queryParams['keywords'] as $keyword) {
                $breakup = explode('|||', $keyword);
                $search = $breakup[0];
                $scoreFieldTitle = $breakup[1];
                $scoreFieldContent = $breakup[2];
                $params['body']['query']['bool']['should'][] = [
                    'multi_match' => [
                        "query"     => $search,
                        "type"      => 'phrase',
                        "fields"    => ["title^{$scoreFieldTitle}", "content^{$scoreFieldContent}"]
                    ]
                ];
            }
        }
        
        $response = $this->elasticsearchClient->search($params);
        
        $resultSet = new ArticleResultSet();
        $resultSet->setArrayObjectPrototype(new ArticleModel);
        $resultSet->elasticsearchInitialize($response['hits']);

        
        return new JsonResponse(['total' => $response['hits']['total'], 'articles' => $resultSet->toArray()]);
    }
}

//GET articles/article/_search
//{
//  "size": 200,
//  "from" : 0,
//  "min_score": 99,
//  "query": {
//    "bool": {
//      "filter": {
//        "range": {
//          "publishDate": {
//            "gte": "2017-11-22"
//          }
//        }
//      },
//      "should": [
//        {
//          "term": {
//            "categories": {
//              "value": "entertainment-films",
//              "boost": 100.0 
//            }
//          }
//        },
//        {
//          "multi_match": {
//            "query": "star wars",
//            "type": "phrase",
//            "fields": ["title^100", "content^0.5"]
//          }        
//        },
//        {
//          "multi_match": {
//            "query": "batman",
//            "type": "phrase",
//            "fields": ["title^100", "content^0.5"]
//          }        
//        },
//        {
//          "multi_match": {
//            "query": "kevin spacey",
//            "type": "phrase",
//            "fields": ["title^100", "content^0.5"]
//          }        
//        },
//        {
//          "multi_match": {
//            "query": "brexit",
//            "type": "phrase",
//            "fields": ["title^100", "content^0.5"]
//          }        
//        }
//      ]
//    }
//  }  
//}