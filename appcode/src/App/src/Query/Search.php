<?php
namespace App\Query;

use App\ResultSet\ArticleResultSet as ArticleResultSet;
use App\Entity\DisplayArticleEntity;

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
                ->setArrayObjectPrototype(new DisplayArticleEntity)
                ->elasticsearchInitialize($results['hits']);
            $data = $resultSet->toArray();
        }
        return [
            'results' => $data,
            'total' => $results['hits']['total']['value'],
        ];
    }
}
