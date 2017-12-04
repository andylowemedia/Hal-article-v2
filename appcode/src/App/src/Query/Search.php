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
        
        
        $resultSet = new ArticleResultSet();
        $resultSet->setArrayObjectPrototype(new ArticleModel);
        $resultSet->elasticsearchInitialize($results['hits']);
        
        return $resultSet->toArray();

    }
}