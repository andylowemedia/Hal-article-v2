<?php
namespace App\Query;

use App\ResultSet\Article as ArticleResultSet;
use App\Model\DisplayArticle as ArticleModel;

/**
 * Description of Search
 *
 * @author andylowe
 */
class Search extends QueryAbstract
{

    public function fetch(array $params)
    {
        $this->buildClientParams($params);

        $client = $this->getClient();

        $results = $client->search($this->params);

        if (empty($results['hits']['hits'])) {
            $data = [];
        } else {
            $resultSet = new ArticleResultSet();
            $resultSet
                ->setArrayObjectPrototype(new ArticleModel)
                ->elasticsearchInitialize($results['hits']);
            $data = $resultSet->toArray();
        }
        return [
            'results' => $data,
            'total' => $results['hits']['total'],
        ];
    }
}
