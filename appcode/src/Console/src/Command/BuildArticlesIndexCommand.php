<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Zend\Db\Sql\Sql, Zend\Db\Sql\Expression, Zend\Db\Sql\Select, Zend\Db\Adapter\Adapter;


use Elasticsearch\Client as ElasticsearchClient;


class BuildArticlesIndexCommand extends Command
{
    private $elasticsearchClient;
    private $articlesDbAdapter;
    
    public function setElasticsearchClient(ElasticsearchClient $elasticsearchClient) : BuildArticlesIndexCommand
    {
        $this->elasticsearchClient = $elasticsearchClient;
        return $this;
    }
    
    public function getElasticsearchclient() : ElasticsearchClient
    {
        return $this->elasticsearchClient;
    }
    
    public function setArticlesDbAdapter($articlesDbAdapter) : BuildArticlesIndexCommand
    {
        $this->articlesDbAdapter = $articlesDbAdapter;
        return $this;
    }
    
    public function getArticlesDbAdapter() : Adapter
    {
        return $this->articlesDbAdapter;
    }
    
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:build-articles-index')

            // the short description shown while running "php bin/console list"
            ->setDescription('')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('')
        ;
 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);
        
        $output->writeln([
            'Build articles index',
            '========================',
            '',
        ]);
        
        
        
        $client = $this->getElasticsearchclient();
        
        $adapter = $this->getArticlesDbAdapter();
         
        $sql = new Sql($adapter);
        
        $fullCategorySelect = clone $sql->select()->from('categories');
        
        $fullCategoryStatement = $sql->prepareStatementForSqlObject($fullCategorySelect);
        $categoryData = $fullCategoryStatement->execute();
        
        $fullCategories = [];
        foreach ($categoryData as $categoryDataRow) {
            $fullCategories[$categoryDataRow['code']] = $categoryDataRow['name'];
        }
        
        $categorySelect = clone $sql->select()
                ->columns(array())
                ->from('article_category_mapper')
                ->join('categories', 
                        'categories.id = article_category_mapper.category_id', 
                        array('categories' => new Expression('GROUP_CONCAT(`categories`.`code`)')))
                ->where(array(
                    'articles.id = article_category_mapper.article_id'
                ));
        
        $topCategorySelect = clone $sql->select()
                ->columns(array())
                ->from('article_category_mapper')
                ->join('categories', 
                        'categories.id = article_category_mapper.category_id', array())
                ->join(array('top_categories' => 'categories'), 'top_categories.id = categories.parent_id', array('categories' => new Expression('GROUP_CONCAT(`top_categories`.`code`)')))
                ->where(array(
                    'articles.id = article_category_mapper.article_id'
                ));
                
        $sourceSelect = clone $sql->select()
                ->columns(array('name'))
                ->from('sources')
                ->where(array(
                    'articles.source_id = sources.id'
                ))
                ;
        
        $featuredArticlesSelect = clone $sql->select()
                ->columns(array('featured' => new Expression('IF (`id`, true, false)')))
                ->from('featured_articles')
                ->where(array(
                    'featured_articles.article_id = articles.id'
                ))
                ->limit(1)
                ;
        
        $select = $sql->select()
                ->columns(array(
                    'id',
                    'slug',
                    'title',
                    'subtitle',
                    'summary',
                    'content',
                    'originalUrl' => 'original_url',
                    'author',
                    'publishDate' => 'publish_date',
                    'date',
                    'articleTypeId' => 'article_type_id',
                    'categories' => $categorySelect,
                    'topCategories' => $topCategorySelect,
                    'source' => $sourceSelect,
                    'featured' => $featuredArticlesSelect,
                    'source_id',
                    'status_id'
                ))
                ->from('articles')
                ->where(array(
//                    'status_id = 2',
//                    'articles.id' => 31995
                ))
//                ->limit(10000)
                ->order('date DESC')
                ;
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        foreach ($results as $result) {
            $categories = explode(',', $result['categories']);
            
            $displayCategory = [];
   
            foreach ($categories as $category) {
                if (isset($fullCategories[$category])) {
                    $displayCategory[] = array(
                        'code' => $category,
                        'name' => $fullCategories[$category]
                    );
                }
            }
            
            $topCategories = explode(',', $result['topCategories']);
            
            $reorderedCategories = array_filter(array_unique(array_merge($topCategories, $categories)));
            sort($reorderedCategories);
            
            
            $imageSelect = clone $sql->select()
                    ->columns(array('url'))
                    ->from('article_images')
                    ->where(array(
                        'article_images.article_id = ?' => $result['id']
                    ))
                    ->order('id asc');
            
            $imageStatement = $sql->prepareStatementForSqlObject($imageSelect);
            $imagesResult = $imageStatement->execute();
            
            $images = $imagesResult->getResource()->fetchAll(\PDO::FETCH_COLUMN);
            
            $mediaSelect = clone $sql->select()
                    ->columns(array('code'))
                    ->from('article_media')
                    ->where(array(
                        'article_media.article_id = ?' => $result['id']
                    ))
                    ->order('id asc');
            
            $mediaStatement = $sql->prepareStatementForSqlObject($mediaSelect);
            $mediaResult = $mediaStatement->execute();
            
            $media = $mediaResult->getResource()->fetchAll(\PDO::FETCH_COLUMN);
            
            $publishDate = new \DateTime($result['publishDate']);
            $date = new \DateTime($result['date']);
                        
            $params = [
                'index' => 'articles',
                'type' => 'article',
                'id' => $result['id'],
                'body' => [ 
                    'title' => $result['title'],
                    'subtitle' => $result['subtitle'],
                    'slug' => strtolower($result['slug']),
                    'summary' => $result['summary'],
                    'content' => $result['content'],
                    'author' => $result['author'],
                    'url' => $result['originalUrl'],
                    'source' => $result['source'],
                    'publishDate' => $publishDate->format('c'),
                    'date' => $date->format('c'),
                    'status' => $result['status_id'],
                    'articleTypeId' => $result['articleTypeId'],
                    'sourceId' => $result['source_id'],
                    
                ]
            ];
            
            if (count($images) > 0) {
                reset($images);
                $params['body']['image'] = current($images);
                $params['body']['images'] = $images;
            }
            
            if (count($media) > 0) {
                reset($media);
                $params['body']['media'] = $media;
            }
            
            if (!is_null($result['featured'])) {
                $params['body']['featured'] = $result['featured'];
            }
            
            if (!empty($reorderedCategories)) {
                $params['body']['categories'] = $reorderedCategories;
                $params['body']['displayCategories'] = $displayCategory;
            }
            print_r($params);
            
            $response = $client->index($params);
            
            print_r($response);
        }
        $end = microtime(true);
        
        $time = $end - $start;
        
        $output->writeln([
            "Built articles index in {$time} seconds",
            '========================',
        ]);
    }
    

}

/*
 * curl -XPUT 'localhost:9200/articles?pretty' -H 'Content-Type: application/json' -d '{"mappings": {"article": {"properties": {"publish_date": {"type": "date" }}}}}'
curl -XPUT 'localhost:9200/my_index/my_type/1?pretty' -H 'Content-Type: application/json' -d'
{ "date": "2015-01-01" }
'
curl -XPUT 'localhost:9200/my_index/my_type/2?pretty' -H 'Content-Type: application/json' -d'
{ "date": "2015-01-01T12:10:30Z" }
'
curl -XPUT 'localhost:9200/my_index/my_type/3?pretty' -H 'Content-Type: application/json' -d'
{ "date": 1420070400001 }
'
curl -XGET 'localhost:9200/my_index/_search?pretty' -H 'Content-Type: application/json' -d'
{
  "sort": { "date": "asc"} 
}
'

 */
