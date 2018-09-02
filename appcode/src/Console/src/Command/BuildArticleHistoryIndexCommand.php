<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;


use Elasticsearch\Client as ElasticsearchClient;

class BuildArticleHistoryIndexCommand extends Command
{
    private $elasticsearchClient;
    private $articlesDbAdapter;

    public function setElasticsearchClient(ElasticsearchClient $elasticsearchClient) : BuildArticleHistoryIndexCommand
    {
        $this->elasticsearchClient = $elasticsearchClient;
        return $this;
    }

    public function getElasticsearchclient() : ElasticsearchClient
    {
        return $this->elasticsearchClient;
    }

    public function setArticlesDbAdapter($articlesDbAdapter) : BuildArticleHistoryIndexCommand
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
            ->setName('app:build-article-history-index')

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
            'Build article history index',
            '========================',
            '',
        ]);



        $client = $this->getElasticsearchclient();

        $adapter = $this->getArticlesDbAdapter();

        $sql = new Sql($adapter);

        $select = $sql->select()->from('source_history');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();


        foreach ($results as $result) {
            $date = new \DateTime($result['date']);

            $params = [
                'index' => 'article-history',
                'type' => 'article-history',
                'id' => $result['id'],
                'body' => [
                    'sourceId' => $result['source_id'],
                    'url' => $result['url'],
                    'message' => $result['message'],
                    'date' => $date->format('c'),
                    'status' => $result['status_id'],
                ]
            ];


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
