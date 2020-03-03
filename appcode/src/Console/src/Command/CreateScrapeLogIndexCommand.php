<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Laminas\Db\Sql\Sql;

use Elasticsearch\Client as ElasticsearchClient;

class CreateIndexCommand extends Command
{
    private $elasticsearchClient;

    public function setElasticsearchClient(ElasticsearchClient $elasticsearchClient) : CreateIndexCommand
    {
        $this->elasticsearchClient = $elasticsearchClient;
        return $this;
    }

    public function getElasticsearchclient() : ElasticsearchClient
    {
        return $this->elasticsearchClient;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:create-index')

            // the short description shown while running "php bin/console list"
            ->setDescription('Create Elasticsearch Index')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);

        $output->writeln([
            'Create Elasticsearch index',
            '==========================',
            '',
        ]);



        $client = $this->getElasticsearchclient();

        $params = [
            'index' => 'articles',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    'article' => [
//                        "_timestamp" => [
//                            "enabled" => "true"
//                        ],
                        '_source' => [ 'enabled' => true],
                        'properties' => [
                            'id' => [
                                'type' => 'integer'
                            ],
                            'slug' => [
                                'type' => 'keyword',
                            ],
                            'title' => [
                                'type' => 'text'
                            ],
                            'subtitle' => [
                                'type' => 'text'
                            ],
                            'summary' => [
                                'type' => 'text'
                            ],
                            'content' => [
                                'type' => 'text'
                            ],
                            'author' => [
                                'type' => 'keyword'
                            ],
                            'url' => [
                                'type' => 'keyword'
                            ],
                            'articleTypeId' => [
                                'type' => 'integer'
                            ],
                            'sourceId' => [
                                'type' => 'integer'
                            ],
                            'source' => [
                                'type' => 'keyword'
                            ],
                            'publishDate' => [
                                'type' => 'date',
                            ],
                            'date' => [
                                'type' => 'date'
                            ],
                            'image' => [
                                'type' => 'keyword'
                            ],
                            'images' => [
                                'type' => 'keyword'
                            ],
                            'media' => [
                                'type' => 'text'
                            ],
                            'featured' => [
                                "type" => "boolean"
                            ],
                            'categories' => [
                                "type" => "keyword"
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $response = $client->indices()->create($params);

        print_r($response);
        die();
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



curl -XPUT http://search-the-hal-project-dkvpkqeip7fitqat3ke5fsrz64.eu-west-1.es.amazonaws.com/analyzed_example -d '{
    "mappings": {
        "mytype": {
            "_source": {"enabled": true},
            "properties": {
                "content": {
                    "type": "string",
                    "index": "not_analyzed"
                }
            }
        }
    }
}';

curl -XPOST http://search-the-hal-project-dkvpkqeip7fitqat3ke5fsrz64.eu-west-1.es.amazonaws.com/analyzed_example/mytype -d '{"content": "test"}';
curl -XPOST http://search-the-hal-project-dkvpkqeip7fitqat3ke5fsrz64.eu-west-1.es.amazonaws.com/analyzed_example/mytype -d '{"content": "test two"}';


curl -XPOST http://search-the-hal-project-dkvpkqeip7fitqat3ke5fsrz64.eu-west-1.es.amazonaws.com/analyzed_example/mytype/_search -d '{"query": {"term": {"content": "test two"}}}';



curl -XPOST http://localhost:9200/articles/article/_search -d '{"query": {"term": {"slug": "I-m-an-ex-Facebook-exec--don-t-believe-what-they-tell-you-about-ads"}}}'

 */
