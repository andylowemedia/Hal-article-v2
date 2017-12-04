<?php
namespace App\Query;

use App\ResultSet\Article as ArticleResultSet;

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
        
        $client = $this->getClient();
                
        $results = new ArticleResultSet($client->search($this->params));
        
        return $results->toArray();

    }
}