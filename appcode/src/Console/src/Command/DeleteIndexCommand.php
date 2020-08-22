<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputArgument;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Client as ElasticsearchClient;

class DeleteIndexCommand extends Command
{
    private $elasticsearchClient;

    public function setElasticsearchClient(ElasticsearchClient $elasticsearchClient) : DeleteIndexCommand
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
            ->setName('app:delete-index')

            // the short description shown while running "php bin/console list"
            ->setDescription('Delete Elasticsearch Index')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('')
//            ->addArgument('data-index', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Delete Elasticsearch index',
            '==========================',
            '',
        ]);

        $client = $this->getElasticsearchclient();

//        $params = ['index' => $input->getArgument('data-index')];
//
//        $response = $client->indices()->delete($params);


        $params = [
            'index' => 'articles',
        ];

        // Delete doc at /my_index/my_type/my_id
        $response = $client->indices()->delete($params);

        print_r($response);

        $output->writeln([
        'Index Deleted',
        ]);
    }
}
