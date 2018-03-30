<?php

namespace App\Action;

use App\Model\Article as ArticleModel;
use App\Mapper\Article as ArticleMapper;

use App\Model\ArticleImage as ArticleImageModel;
use App\Mapper\ArticleImage as ArticleImageMapper;

use App\Model\ArticleMedia as ArticleMediaModel;
use App\Mapper\ArticleMedia as ArticleMediaMapper;

use App\Model\ArticleCategory as ArticleCategoryModel;
use App\Mapper\ArticleCategory as ArticleCategoryMapper;

use App\Model\ArticleKeyword as ArticleKeywordModel;
use App\Mapper\ArticleKeyword as ArticleKeywordMapper;

use App\Model\FeaturedArticle as FeaturedArticleModel;
use App\Mapper\FeaturedArticle as FeaturedArticleMapper;

//use App\Model\SourceHistory as SourceHistoryModel;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

use Elasticsearch\ClientBuilder;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;


class AddAction implements ServerMiddlewareInterface
{
    private $hosts;
    private $apiConfig;
    private $dbAdapter;
    private $articleMapper;
    private $articleImageMapper;
    private $articleCategoryMapper;
    private $articleKeywordMapper;
    private $categories = [];
    private $systemCategories = [];
    private $featuredSites;
    private $sources = [];

    public function __construct(array $hosts, array $apiConfig, array $featuredSites, Adapter $dbAdapter, ArticleMapper $articleMapper, ArticleImageMapper $articleImageMapper, ArticleMediaMapper $articleMediaMapper, ArticleCategoryMapper $articleCategoryMapper, ArticleKeywordMapper $articleKeywordMapper, FeaturedArticleMapper $featuredArticleMapper)
    {
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
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : JsonResponse
    {
        $data = $request->getParsedBody();
        
        $this->fetchSources();
        $this->fetchCategories();
        
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
            $this->saveElasticsearch($article, $images, $media, $categories, $keywords, $featuredSites);
            $responseData = [
                'success' => true,
                'message' => 'Article added',
                'article' => $article->toArray()
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
    
    private function saveElasticsearch(ArticleModel $article, array $images, array $media, array $categories, array $keywords, array $featuredSites)
    {
        $client = ClientBuilder::create()
                ->setHosts($this->hosts)
                ->build();
        
        $params = [
            'index' => 'articles',
            'type' => 'article',
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
        
        foreach ($categories as $category) {
            $categoryData = $this->categories[$category->categoryId];
            $params['body']['categories'][] = $categoryData['code'];
            $params['body']['displayCategories'][] = ['name' => $categoryData['name'], 'code' => $categoryData['code']];
            if ($categoryData['parentId']) {
                $params['body']['categories'][] = $this->categories[$categoryData['parentId']]['code'];
                //$params['body']['displayCategories'][] = $this->categories[$categoryData['parentId']]['name'];
            }
        }
        
        foreach ($keywords as $keyword) {
            $params['body']['keywords'][] =  $keyword->keyword;
        }
        

        $client->index($params);
        return $this;
    }
    
    private function mapToArticleModel(array $data) : ArticleModel
    {
        $dateTime = new \DateTime();
        
        $publishDate = new \DateTime($data['publishDate']);
        
        $article = new ArticleModel(array(
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
        ));
        
        $article->createSlug();
        
        return $article;
    }
    
    private function mapToArticleImageModels(array $data) : array
    {
        $images = [];
        if (isset($data['images'])) {
            foreach ($data['images'] as $url) {
                $images[] = new ArticleImageModel(array(
                    'url'       => $url,
                    'statusId'  => 2
                ));
            }
        }
        
        return $images;
    }
    
    private function mapToArticleMediaModels(array $data) : array
    {
        $images = [];
        if (isset($data['media'])) {
            foreach ($data['media'] as $url) {
                $images[] = new ArticleMediaModel(array(
                    'code'       => $url,
                    'statusId'  => 2
                ));
            }
        }
        
        return $images;
    }
    
    private function mapToArticleCategoryModels(array $data) : array
    {
        $categories = [];
        
        
        if (isset($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                if (isset($this->categories[$categoryId])) {
                    $categories[] = new ArticleCategoryModel(array(
                        'categoryId'  => $categoryId,
                    ));
                }
            }
        }
        return $categories;    
    }
    
    private function mapToArticleKeywordModels(array $data) : array
    {
        $keywords = [];
        
        
        if (isset($data['keywords'])) {
            foreach ($data['keywords'] as $keyword) {
                $keywords[] = new ArticleKeywordModel(array(
                    'keyword'  => $keyword,
                ));
            }
        }
        return $keywords;    
    }
        
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
    
    private function saveDatabase(ArticleModel $article, array $images, array $media, array $categories, array $keywords, array $featuredArticles)
    {
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
    
    private function fetchCategories()
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->apiConfig['category'] . "/list?all-categories=true");
        
        $data = json_decode($res->getBody(), true);
        
        foreach ($data['categories'] as $row) {
            $this->systemCategories[$row['code']] = $row['id'];
            $this->categories[$row['id']] = ['code' => $row['code'], 'name' => $row['name'], 'parentId' => $row['parentId']];
        }
        
        return $this;
    }
    
    private function fetchSources()
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
}
