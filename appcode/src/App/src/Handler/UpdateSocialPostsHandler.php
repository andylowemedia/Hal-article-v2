<?php

namespace App\Handler;

use App\Mapper\MapperAbstract;
use Laminas\Db\Adapter\Adapter;
use App\Entity\ArticleSocialMediaPostEntity as ArticleSocialMediaPostModel;

use Elasticsearch\ClientBuilder;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;

class UpdateSocialPostsHandler implements RequestHandlerInterface
{
    private $hosts;
    private $apiConfig;
    private $dbAdapter;
    private $articleSocialMediaPostMapper;
    private $socialMediaMapper;

    public function __construct(
        array $hosts,
        array $apiConfig,
        Adapter $dbAdapter,
        MapperAbstract $articleSocialMediaPostMapper,
        MapperAbstract $socialMediaMapper
    ) {
        $this->apiConfig                        = $apiConfig;
        $this->hosts                            = $hosts;
        $this->dbAdapter                        = $dbAdapter;
        $this->articleSocialMediaPostMapper     = $articleSocialMediaPostMapper;
        $this->socialMediaMapper                = $socialMediaMapper;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = \json_decode($request->getBody()->getContents(), true);

        $connection = $this->dbAdapter->driver->getConnection();
        $connection->beginTransaction();

        try {

            $this->saveDatabase($data);
            $this->saveElasticsearch($data);

            $responseData = [
                'success' => true,
                'message' => 'ArticleMapper Updated',
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

    private function saveElasticsearch(array $data)
    {

        $client = ClientBuilder::create()
                ->setHosts($this->hosts)
                ->build();

        $article = $client->get([
            'index' => 'articles',
            'type' => 'article',
            'id' => $data['article-id']
        ]);


        $posted = [];

        if (isset($article['_source']['posted'])) {
            $posted = $article['_source']['posted'];
        }

        if (isset($posted[$data['social-media-name']]) && !\in_array($data['site-name'], $posted[$data['social-media-name']])) {
            return $this;
        }

        $posted[$data['social-media-name']][] = $data['site-name'];

        $params = [
            'index' => 'articles',
            'type' => 'article',
            'id' => $data['article-id'],
            'body' => [
                'doc' => [
                    'posted' => $posted
                ]
            ]
        ];

        $client->update($params);

        return $this;
    }


    private function saveDatabase(array $data) {
        $articleSocialMediaPost = new ArticleSocialMediaPostModel([
            'article_id' => $data['article-id'],
            'site_id' => $data['site-id'],
            'social_media_id' => $data['social-media-id'],
            'posted_datetime' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        $this->articleSocialMediaPostMapper->save($articleSocialMediaPost);

        return $this;
    }
}
