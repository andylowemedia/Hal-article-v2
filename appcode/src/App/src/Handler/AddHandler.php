<?php
declare(strict_types=1);
namespace App\Handler;

use App\Entity\ArticleEntity as ArticleEntity;
use App\Mapper\ArticleMapper as ArticleMapper;

use App\Entity\ArticleImageEntity as ArticleImageModel;
use App\Mapper\ArticleImageMapper as ArticleImageMapper;

use App\Entity\ArticleMediaEntity as ArticleMediaModel;
use App\Mapper\ArticleMediaMapper as ArticleMediaMapper;

use App\Entity\ArticleCategoryEntity as ArticleCategoryModel;
use App\Mapper\ArticleCategoryMapper as ArticleCategoryMapper;

use App\Entity\ArticleKeywordEntity as ArticleKeywordModel;
use App\Mapper\ArticleKeywordMapper as ArticleKeywordMapper;

use App\Entity\FeaturedArticleEntity as FeaturedArticleModel;
use App\Mapper\FeaturedArticleMapper as FeaturedArticleMapper;

//use App\Model\SourceHistoryMapper as SourceHistoryModel;

use Aws\Sqs\SqsClient;
use Psr\Http\Message\ResponseInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

use Elasticsearch\ClientBuilder;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class AddHandler implements RequestHandlerInterface
{
    /**
     * @var array
     */
    private array $hosts = [];

    /**
     * @var array
     */
    private array $apiConfig;

    /**
     * @var Adapter
     */
    private Adapter $dbAdapter;

    /**
     * @var ArticleMapper
     */
    private ArticleMapper $articleMapper;

    /**
     * @var ArticleImageMapper
     */
    private ArticleImageMapper $articleImageMapper;

    /**
     * @var ArticleMediaMapper
     */
    private ArticleMediaMapper $articleMediaMapper;

    /**
     * @var ArticleCategoryMapper
     */
    private ArticleCategoryMapper $articleCategoryMapper;

    /**
     * @var ArticleKeywordMapper
     */
    private ArticleKeywordMapper $articleKeywordMapper;

    /**
     * @var FeaturedArticleMapper
     */
    private FeaturedArticleMapper $featuredArticleMapper;

    /**
     * @var array
     */
    private array $categories = [];

    /**
     * @var array
     */
    private array $systemCategories = [];

    /**
     * @var array
     */
    private array $featuredSites = [];

    /**
     * @var array
     */
    private array $sources = [];

    /**
     * @var SqsClient
     */
    private SqsClient $sqsClient;

    /**
     * @var string
     */
    private string $queue;

    /**
     * AddHandler constructor.
     * @param array $hosts
     * @param array $apiConfig
     * @param array $featuredSites
     * @param Adapter $dbAdapter
     * @param ArticleMapper $articleMapper
     * @param ArticleImageMapper $articleImageMapper
     * @param ArticleMediaMapper $articleMediaMapper
     * @param ArticleCategoryMapper $articleCategoryMapper
     * @param ArticleKeywordMapper $articleKeywordMapper
     * @param FeaturedArticleMapper $featuredArticleMapper
     */
    public function __construct(
        array $hosts,
        array $apiConfig,
        array $featuredSites,
        Adapter $dbAdapter,
        ArticleMapper $articleMapper,
        ArticleImageMapper $articleImageMapper,
        ArticleMediaMapper $articleMediaMapper,
        ArticleCategoryMapper $articleCategoryMapper,
        ArticleKeywordMapper $articleKeywordMapper,
        FeaturedArticleMapper $featuredArticleMapper,
        SqsClient $sqsClient,
        string $queue
    ) {

        $this->apiConfig                = $apiConfig;
        $this->hosts                    = $hosts;
        $this->dbAdapter                = $dbAdapter;
        $this->articleMapper            = $articleMapper;
        $this->articleImageMapper       = $articleImageMapper;
        $this->articleMediaMapper       = $articleMediaMapper;
        $this->articleCategoryMapper    = $articleCategoryMapper;
        $this->articleKeywordMapper     = $articleKeywordMapper;
        $this->featuredArticleMapper    = $featuredArticleMapper;
        $this->featuredSites            = $featuredSites;
        $this->sqsClient                = $sqsClient;
        $this->queue                    = $queue;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \App\Entity\EntityException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = \json_decode($request->getBody()->getContents(), true);

        $this->fetchSources();
//        $this->fetchCategories();

        $article = $this->mapToArticleModel($data);

        $images = $this->mapToArticleImageModels($data);

        $media = $this->mapToArticleMediaModels($data);

        $categories = $this->mapToArticleCategoryModels($data);

        $keywords = $this->mapToArticleKeywordModels($data);

        $featuredSites = $this->mapToFeaturedArticleModel($data);

        $connection = $this->dbAdapter->driver->getConnection();
        $connection->beginTransaction();

        try {
            $this->saveDatabase($article, $images, $media, $categories, $keywords, $featuredSites);
            $this->saveElasticsearch($article, $images, $media, $data['categoryCodes'], $data['displayCategories'], $keywords, $featuredSites);
            $responseData = [
                'success' => true,
                'message' => 'Article added',
                'article' => $article->toArray()
            ];
            unset($responseData['article']['date']);
            $responseData['article']['images'] = $images;
            $responseData['article']['media'] = $media;
            $responseData['article']['categoryIds'] = $categories;
            $responseData['article']['categoryCodes'] = $data['categoryCodes'];
            $responseData['article']['displayCategories'] = $data['displayCategories'];
            $responseData['article']['keywords'] = $keywords;
            $responseData['article']['featured'] = $featuredSites;
            $responseCode = 201;
            $connection->commit();
            $statusId = 4;
            $outputMessage = "Saved {$data['url']}";
        } catch (\Exception $error) {
            $responseData = [
                'success' => false,
                'message' => 'An error has occurred : ' . $error->getMessage(),
//                'trace' => $error->getTraceAsString()
            ];
            $connection->rollback();
            if (strpos(strtolower($error->getMessage()), 'duplicate') !== false) {
                $statusId = 6;
                $responseCode = 409;
            } else {
                $statusId = 5;
                $responseCode = 500;
            }

            $outputMessage = $error->getMessage();
        }

        $this->sendEvent($data, \json_encode($responseData), $statusId, $responseCode, $outputMessage);
        return new JsonResponse($responseData, $responseCode);
    }

    /**
     * @param ArticleEntity $article
     * @param array $images
     * @param array $media
     * @param array $categoryCodes
     * @param array $displayCategories
     * @param array $keywords
     * @param array $featuredSites
     * @return AddHandler
     * @throws \App\Entity\EntityException
     *@todo Refactor into a service
     */
    private function saveElasticsearch(
        ArticleEntity $article,
        array $images,
        array $media,
        array $categoryCodes,
        array $displayCategories,
        array $keywords,
        array $featuredSites
    ): self {
        $client = ClientBuilder::create()
                ->setHosts($this->hosts)
                ->build();

        $params = [
            'index' => 'articles',
            'id' => $article->id,
            'body' => [
                'title' => $article->title,
                'subtitle' => $article->subtitle,
                'slug' => $article->slug,
                'summary' => $article->summary,
                'content' => $article->content,
                'author' => $article->author,
                'url' => $article->originalUrl,
                'source' => $this->sources[$article->sourceId],
                'publishDate' => $article->getPublishDate('c'),
                'date' => $article->getDate('c'),
                'status' => 2,
                'articleTypeId' => $article->articleTypeId,
                'sourceId' => $article->sourceId
            ]
        ];

        if (count($images) > 0) {
            $image = current($images);
            $params['body']['image'] = $image->url;

            $imageUrls = [];
            foreach ($images as $imageModel) {
                $imageUrls[] = $imageModel->url;
            }
            $params['body']['images'] = $imageUrls;
        }

        if (count($media) > 0) {
            $mediaCode = [];
            foreach ($media as $mediaModel) {
                $mediaCode[] = $mediaModel->code;
            }
            $params['body']['media'] = $mediaCode;
        }


        $params['body']['featured'] = count($featuredSites) > 0 ? true : false;

        $params['body']['categories'] = $categoryCodes;

        $params['body']['displayCategories'] = $displayCategories;

        foreach ($keywords as $keyword) {
            $params['body']['keywords'][] =  $keyword->keyword;
        }


        $client->index($params);
        return $this;
    }

    /**
     * @param array $data
     * @return ArticleEntity
     * @throws \App\Entity\EntityException
     *@todo Refactor into a service
     */
    private function mapToArticleModel(array $data) : ArticleEntity
    {
        $dateTime = new \DateTime();

        $publishDate = new \DateTime($data['publishDate']);

        $article = new ArticleEntity([
            'title'             => $data['title'],
            'subtitle'          => $data['subtitle'],
            'summary'           => $data['summary'],
            'content'           => $data['content'],
            'author'            => $data['author'],
            'sourceId'          => $data['sourceId'],
            'originalUrl'       => $data['url'],
            'articleTypeId'     => $data['articleTypeId'],
            'publishDate'       => $publishDate->format('Y-m-d H:i:s'),
            'date'              => $dateTime->format('Y-m-d H:i:s'),
            'statusId'          => 2,
        ]);

        $article->createSlug();

        return $article;
    }

    /**
     * @todo Refactor into a service
     * @param array $data
     * @return array
     */
    private function mapToArticleImageModels(array $data) : array
    {
        $images = [];
        if (isset($data['images'])) {
            foreach ($data['images'] as $url) {
                $images[] = new ArticleImageModel([
                    'url'       => $url,
                    'statusId'  => 2
                ]);
            }
        }

        return $images;
    }

    /**
     * @todo Refactor into a service
     * @param array $data
     * @return array
     */
    private function mapToArticleMediaModels(array $data) : array
    {
        $images = [];
        if (isset($data['media'])) {
            foreach ($data['media'] as $url) {
                $images[] = new ArticleMediaModel([
                    'code'       => $url,
                    'statusId'  => 2
                ]);
            }
        }

        return $images;
    }

    /**
     * @todo Refactor into a service
     * @param array $data
     * @return array
     */
    private function mapToArticleCategoryModels(array $data) : array
    {
        $categories = [];


        if (isset($data['categoryIds'])) {
            foreach ($data['categoryIds'] as $categoryId) {
                $categories[] = new ArticleCategoryModel([
                    'categoryId'  => (int) $categoryId,
                ]);
            }
        }
        return $categories;
    }

    /**
     * @todo Refactor into a service
     * @param array $data
     * @return array
     */
    private function mapToArticleKeywordModels(array $data) : array
    {
        $keywords = [];


        if (isset($data['keywords'])) {
            foreach ($data['keywords'] as $keyword) {
                $keywords[] = new ArticleKeywordModel([
                    'keyword'  => $keyword,
                ]);
            }
        }
        return $keywords;
    }

    /**
     * @todo Refactor into a service
     * @param array $data
     * @return array
     */
    private function mapToFeaturedArticleModel(array $data) : array
    {
        $featured = [];

        if (isset($data['headline']) && $data['headline']) {
            foreach ($this->featuredSites as $featuredSiteId) {
                $featured[] = new FeaturedArticleModel([
                    'siteId' => $featuredSiteId
                ]);
            }
        }

        return $featured;
    }

    /**
     * @param ArticleEntity $article
     * @param array $images
     * @param array $media
     * @param array $categories
     * @param array $keywords
     * @param array $featuredArticles
     * @return AddHandler
     * @throws \App\Mapper\MapperException
     *@todo Refactor into a service
     */
    private function saveDatabase(
        ArticleEntity $article,
        array $images,
        array $media,
        array $categories,
        array $keywords,
        array $featuredArticles
    ): self {
        $this->articleMapper->save($article);

        foreach ($images as $image) {
            $image->articleId = $article->id;
            $this->articleImageMapper->save($image);
        }

        foreach ($media as $mediaRow) {
            $mediaRow->articleId = $article->id;
            $this->articleMediaMapper->save($mediaRow);
        }

        foreach ($categories as $category) {
            $category->articleId = $article->id;
            $this->articleCategoryMapper->save($category);
        }

        foreach ($keywords as $keyword) {
            $keyword->articleId = $article->id;
            $this->articleKeywordMapper->save($keyword);
        }

        foreach ($featuredArticles as $featuredArticle) {
            $featuredArticle->articleId = $article->id;
            $this->featuredArticleMapper->save($featuredArticle);
        }

        return $this;
    }

    /**
     * @todo Needs to be refactored into a service
     * @return AddHandler
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function fetchCategories(): self
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->apiConfig['category'] . "/list?all-categories=true");

        $data = \json_decode($res->getBody()->getContents(), true);

        foreach ($data['categories'] as $row) {
            $this->systemCategories[$row['code']] = $row['id'];
            $this->categories[$row['id']] = [
                'code' => $row['code'],
                'name' => $row['name'],
                'parentId' => $row['parentId']
            ];
        }

        return $this;
    }

    /**
     * @todo Needs to be refactored into a service
     * @return AddHandler
     */
    private function fetchSources(): self
    {
        $sql = new Sql($this->dbAdapter);

        $select = $sql->select()
                ->from('sources');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        foreach ($results as $result) {
            $this->sources[$result['id']] = $result['name'];
        }

        return $this;
    }

    private function sendEvent(array $data, string $outputMessage, int $statusId, int $responseCode, string $message): void
    {
        $this->sqsClient->sendMessage([
            'DelaySeconds' => 10,
            'MessageAttributes' => [
                "Type" => [
                    'DataType' => "String",
                    'StringValue' => "article"
                ],
                "Event" => [
                    'DataType' => "String",
                    'StringValue' => "article-add"
                ],
                "DateTime" => [
                    'DataType' => "String",
                    'StringValue' => date('Y-m-d H:i:s')
                ],
                "Url" => [
                    'DataType' => "String",
                    'StringValue' => $data['url']
                ],
                "SourceId" => [
                    'DataType' => "Number",
                    'StringValue' => $data['sourceId']
                ],
                "StatusId" => [
                    'DataType' => "Number",
                    'StringValue' => $statusId
                ],
                "ResponseCode" => [
                    'DataType' => "Number",
                    'StringValue' => $responseCode
                ],
                "ResponseMessage" => [
                    'DataType' => "String",
                    'StringValue' => $message
                ]
            ],
            'MessageBody' => $outputMessage,
            'QueueUrl' => $this->queue
        ]);
    }
}
