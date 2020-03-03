<?php
namespace App\Query;

use App\Entity\DisplayArticleEntity as ArticleModel;
use App\ResultSet\ArticleResultSet as ArticleResultSet;

/**
 * Description of Search
 *
 * @author andylowe
 */
class Summary extends QueryAbstract
{

    public function fetch(array $params)
    {
        $this->buildClientParams($params);

        $results = $this->getClient()->search($this->getParams());

        $resultSet = new ArticleResultSet();
        $resultSet
            ->setArrayObjectPrototype(new ArticleModel)
            ->elasticsearchInitialize($results['hits']);

        return $resultSet->toArray();
    }
}
