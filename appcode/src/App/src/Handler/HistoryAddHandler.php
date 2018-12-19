<?php
namespace App\Handler;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

use App\Mapper\SourceHistory as SourceHistoryMapper;
use App\Model\SourceHistory as SourceHistoryModel;

use Elasticsearch\Client as ElasticsearchClient;
use Zend\Db\Adapter\Adapter;

class HistoryAddHandler implements RequestHandlerInterface
{
    private $sourceHistoryMapper;
    private $elasticsearchClient;
    private $dbAdapter;

    public function __construct(SourceHistoryMapper $sourceHistoryMapper, ElasticsearchClient $elasticsearchClient, Adapter $dbAdapter)
    {
        $this->sourceHistoryMapper  = $sourceHistoryMapper;
        $this->elasticsearchClient  = $elasticsearchClient;
        $this->dbAdapter            = $dbAdapter;
    }

    public function handle(ServerRequestInterface $request) : \Psr\Http\Message\ResponseInterface
    {
        $data = $request->getParsedBody();
        $client = $this->elasticsearchClient;

        $connection = $this->dbAdapter->driver->getConnection();
        $connection->beginTransaction();

        try {
            $sourceHistory = new SourceHistoryModel;

            $sourceHistory
                    ->setDate($data['date'])
                    ->setMessage($data['message'])
                    ->setSourceId($data['sourceId'])
                    ->setUrl($data['url'])
                    ->setStatusId($data['status'])
                    ;

            $this->sourceHistoryMapper->save($sourceHistory);

            $date = new \DateTime($data['date']);

            $params = [
                'index' => 'article-history',
                'type' => 'article-history',
                'id' => $sourceHistory->id,
                'body' => [
                    'sourceId' => $sourceHistory->sourceId,
                    'url' => $sourceHistory->url,
                    'message' => $sourceHistory->message,
                    'date' => $date->format('c'),
                    'status' => $sourceHistory->statusId,
                ]
            ];


            $client->index($params);

            $responseData = [
                'success' => true,
                'message' => 'Article History added',
                'article' => $sourceHistory->toArray()
            ];
            $responseCode = 200;
            $connection->commit();
        } catch (\Exception $ex) {
            $responseData = [
                'success' => false,
                'message' => 'An error has occurred : ' . $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ];
            $responseCode = 500;
            $connection->rollback();
        }
        return new JsonResponse($responseData, $responseCode);
    }
}
