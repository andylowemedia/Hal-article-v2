<?php
namespace App\Query;

use Elasticsearch\ClientBuilder;

/**
 * Description of QueryAbstract
 *
 * @author andylowe
 */
abstract class QueryAbstract
{
    protected $hosts = [];

    protected $params = [];

    private $client;

    public function __construct(array $hosts)
    {
        $this->setHosts($hosts);
    }

    public function setHosts(array $hosts)
    {
        $this->hosts = $hosts;
        return $this;
    }

    public function getHosts()
    {
        if (!is_array($this->hosts) || empty($this->hosts)) {
            throw new \InvalidArgumentException('Hosts must be an array of IP addresses');
        }
        return $this->hosts;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function buildClient()
    {
        $this->setClient(ClientBuilder::create()
                ->setHosts($this->getHosts())
                ->build());
        return $this;
    }

    protected function buildClientParams(array $params)
    {
//        echo "<pre>";
//        print_r($params);
//        die;
//
        if (!isset($params['index'])) {
            throw new \InvalidArgumentException('Index must be set');
        }

        if (!isset($params['type'])) {
            throw new \InvalidArgumentException('Type must be set');
        }

        $size = isset($params['size']) ? $params['size'] : 100;

        $from = isset($params['page']) ? ($params['page'] - 1) : 0;
        if ($from < 0) {
            $from = 0;
        }

        $this->params = [
            'index' => $params['index'],
            'type' => $params['type'],
            'size' => $size,
            'from' => $from,
            'body' => [
                '_source' => [
                    'slug', 'title', 'subtitle', 'summary', 'image', 'publishDate', 'author', 'source', 'categories', 'displayCategories', 'keywords', 'url'
                ],
                'track_scores' => true,
                "min_score" => 1,
                'query' => [
                    "bool" => []
                ]
            ]
        ];

        if (isset($params['date-fr'])) {
            $params['date-fr'] = str_replace(' ', '+', $params['date-fr']);
        }

        if (isset($params['date-to'])) {
            $params['date-to'] = str_replace(' ', '+', $params['date-to']);
        }

        if (isset($params['sort'])) {
            if (is_array($params['sort'])) {
                $sort = $params['sort'];
            } else {
                $sort = [$params['sort']];
            }
            $this->params['sort'] = $sort;
        }

        if (isset($params['search'])) {
            $this->params['body']['query']['bool']['must'][] =
                    [
                        "multi_match" => [
                            "query" => $params['search'],
                            "type" => "phrase",
                            "fields" => ["title^100", "content^0.5"]
                        ]
                    ];
        }

        if (isset($params['keywords'])) {
            $keywords = [];
            foreach ($params['keywords'] as $keyword) {
                $keywords['should'][] = ["term" => ["keywords" => $keyword]];
            }
            $this->params['body']['query']['bool']['must']['bool'] = $keywords;
            $this->params['body']['query']['bool']['must_not'] = [
                "term" => ["slug" => $params['slug']]
            ];
        }

        if (isset($params["exists"])) {
            foreach ($params["exists"] as $field) {
                $this->params['body']['query']['bool']['must'][] =
                    ['exists' => [
                        'field' => $field
                    ]];
            }
        }

        if (isset($params["excludes"])) {
            foreach ($params["excludes"] as $key => $field) {
                foreach ($field as $value) {
                    $this->params['body']['query']['bool']['must_not'][]['match_phrase'] = [$key => $value];
                }
            }
        }

        if (isset($params['article-type'])) {
            $this->params['body']['query']['bool']['must'][] =
                        ["match_phrase" => ['articleTypeId' => $params['article-type']]];
        }


        if (isset($params['sourceId']) && is_array($params['sourceId'])) {
            foreach ($params['sourceId'] as $sourceId) {
                $this->params['body']['query']['bool']['must'][] =
                        ["match_phrase" => ['sourceId' => $sourceId]];
            }
        }

        if (isset($params['category']) && is_array($params['category'])) {
            foreach ($params['category'] as $category) {
                $this->params['body']['query']['bool']['must'][] =
                        ["match_phrase" => ['categories' => $category]];
            }
        }

        if (isset($params['filter']) && is_array($params['filter'])) {
            foreach ($params['filter'] as $key => $value) {
                $this->params['body']['query']['bool']['must'][] =
                        ["match_phrase" => [$key => $value]];
            }
        }

        if (isset($params['date-fr']) && isset($this->params['body']['query']['bool']['must'])) {
            $this->params['body']['query']['bool']['filter']["range"]["publishDate"]["gte"] = $params['date-fr'];

            if (isset($params['date-to'])) {
                $this->params['body']['query']['bool']['filter']["range"]["publishDate"]["lte"] = $params['date-to'];
            }
        } elseif (isset($params['date-fr'])) {
            $this->params['body']['query']['bool']['must']["range"]["publishDate"]["gte"] = $params['date-fr'];

            if (isset($params['date-to'])) {
                $this->params['body']['query']['bool']['must']["range"]["publishDate"]["lte"] = $params['date-to'];
            }
        }

//        echo "<pre>";
//        print_r($this->params);
//        die;

        return $this;
    }
}
