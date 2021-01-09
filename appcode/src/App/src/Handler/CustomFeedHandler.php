<?php
namespace App\Handler;

use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Elasticsearch\Client as ElasticsearchClient;
use App\ResultSet\ArticleResultSet as ArticleResultSet;
use App\Entity\DisplayArticleEntity as ArticleModel;

class CustomFeedHandler implements RequestHandlerInterface
{
    private $elasticsearchClient;

    public function __construct(ElasticsearchClient $elasticsearchClient)
    {
        $this->elasticsearchClient = $elasticsearchClient;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $params = [
            'index' => 'articles',
            'body' => [
                'size' => $queryParams['size'],
                'from' => $queryParams['from'],
                'min_score' => $queryParams['threshold'],
                '_source' => [
                    'id',
                    'slug',
                    'title',
                    'subtitle',
                    'summary',
                    'image',
                    'publishDate',
                    'author',
                    'source',
                    'categories',
                    'displayCategories',
                    'keywords',
                    'posted',
                    'url',
                ],
                'track_scores' => true,
                'query' => [
                    'bool' => [
                        "must" => [
                            [
                                "match" => [
                                    "articleTypeId" => "1"
                                ]
                            ],
                        ],
                        'filter' => [
                            'range' => [
                                'publishDate' => [
                                    'gte' => $queryParams['start-date']
                                ]
                            ],

                        ]
                    ]
                ]
            ]
        ];

        if (isset($queryParams['sort'])) {
            $params['sort'] = $queryParams['sort'];
        }

        if (isset($queryParams['featured'])) {
            $params['body']['query']['bool']['must'][] =
                ['exists' => [
                    'field' => 'featured'
                ]];
        }

        if (isset($queryParams['image'])) {
            $params['body']['query']['bool']['must'][] =
                ['exists' => [
                    'field' => 'image'
                ]];
        }

        if (isset($queryParams['categories'])) {
            foreach (\explode(',', $queryParams['categories']) as $category) {
                $breakup = explode('|||', $category);
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

        if (isset($queryParams['terms'])) {
            foreach (\explode(',', $queryParams['terms']) as $keyword) {
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

        if ($response['hits']['total']['value'] === 0) {
            return new EmptyResponse(404);
        }

        $resultSet = new ArticleResultSet();
        $resultSet->setArrayObjectPrototype(new ArticleModel);
        $resultSet->elasticsearchInitialize($response['hits']);

        $results = $resultSet->toArray();

        return new JsonResponse([
            'total' => $response['hits']['total']['value'],
            'count' => count($results),
            'maxScore' => (int) $response['hits']['max_score'],
            'articles' => $results
        ]);
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
