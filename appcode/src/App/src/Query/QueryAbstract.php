<?php
namespace App\Query;

use Elasticsearch\Client;

/**
 * Class QueryAbstract
 * @package App\Query
 */
abstract class QueryAbstract
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var Client
     */
    private $client;

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set Elasticsearch client
     * @param Client $client
     * @return QueryAbstract
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get Elasticsearch client
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Build client parameters
     * @param array $params
     * @return QueryAbstract
     */
    public function buildClientParams(array $params): self
    {
        if (!isset($params['index'])) {
            throw new \InvalidArgumentException('Index must be set', 400);
        }

        $size = isset($params['size']) ? $params['size'] : 102;

        $from = isset($params['page']) ? ($params['page'] * $size) : 0;
        if ($from < 0) {
            $from = 0;
        }

        $this->params = [
            'index' => $params['index'],
            'size' => $size,
            'from' => $from,
            'body' => [
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
//                'track_scores' => true,
                'query' => [
                    "bool" => []
                ]
            ]
        ];

        if (isset($params['min-score'])) {
            $this->params['body']['min_source'] = $params['min-score'];
        }

        $this->buildFilteringParams($params);

        $this->buildSortParams($params);

        $this->buildDateParams($params);

        return $this;
    }

    /**
     * Build filtering parameters into query
     * @param array $params
     * @return QueryAbstract
     */
    private function buildFilteringParams(array $params): self
    {
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
            foreach (\explode(',', $params['keywords']) as $keyword) {
                $keywords['should'][] = ["term" => ["keywords" => $keyword]];
            }
            $this->params['body']['query']['bool'] = $keywords;
//            $this->params['body']['query']['bool']['must_not'] = [
//                "term" => ["slug" => $params['slug']]
//            ];
        }


        if (isset($params["exists"])) {
            foreach (\explode(',', $params["exists"]) as $field) {
                $this->params['body']['query']['bool']['must'][] =
                    ['exists' => [
                        'field' => $field
                    ]];
            }
        }

        if (isset($params["not-exists"])) {
            foreach (\explode(',', $params["not-exists"]) as $field) {
                $this->params['body']['query']['bool']['must_not'][] =
                    ['exists' => [
                        'field' => $field
                    ]];
            }
        }

        if (isset($params["excludes"])) {
            foreach ($params["excludes"] as $key => $field) {
                foreach (\explode(',', $field) as $value) {
                    $this->params['body']['query']['bool']['must_not'][]['match_phrase'] = [$key => $value];
                }
            }
        }

        if (isset($params['filter']) && is_array($params['filter'])) {
            foreach ($params['filter'] as $key => $value) {
                $this->params['body']['query']['bool']['must'][] =
                    ["match_phrase" => [$key => $value]];
            }
        }

        if (isset($params['not-filter']) && is_array($params['not-filter'])) {
            foreach ($params['not-filter'] as $key => $value) {
                $this->params['body']['query']['bool']['must_not'][] =
                    ["match_phrase" => [$key => $value]];
            }
        }

        return $this;
    }

    /**
     * Build sort parameters into query
     * @param array $params
     * @return QueryAbstract
     */
    private function buildSortParams(array $params): self
    {
        if (isset($params['sort'])) {
            $this->params['sort'] = \explode(',', $params['sort']);
        }

        return $this;
    }

    /**
     * Build date parameters into query
     * @param array $params
     * @return QueryAbstract
     */
    private function buildDateParams(array $params): self
    {
        if (isset($params['date-fr'])) {
            $params['date-fr'] = str_replace(' ', '+', $params['date-fr']);
        }

        if (isset($params['date-to'])) {
            $params['date-to'] = str_replace(' ', '+', $params['date-to']);
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

        return $this;
    }
}
