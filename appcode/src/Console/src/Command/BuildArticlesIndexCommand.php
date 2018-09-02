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

class BuildArticlesIndexCommand extends Command
{
    private $elasticsearchClient;
    private $articlesDbAdapter;
    private $apiConfig;

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

    public function setApiConfig(array $apiConfig) : BuildArticlesIndexCommand
    {
        $this->apiConfig = $apiConfig;
        return $this;
    }

    public function getApiConfig() : array
    {
        return $this->apiConfig;
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

        $guzzleClient = new \GuzzleHttp\Client();
        $res = $guzzleClient->request('GET', $this->getApiConfig()['category'] . "/list?all-categories=true");

        $categoryData = json_decode($res->getBody());

        $fullCategories = [];
        foreach ($categoryData->categories as $categoryRow) {
            $fullCategories[$categoryRow->id] = $categoryRow;
        }

        $client = $this->getElasticsearchclient();

        $adapter = $this->getArticlesDbAdapter();

        $sql = new Sql($adapter);

        $categorySelect = clone $sql->select()
                ->columns(['categoryIds' => new Expression('GROUP_CONCAT(`category_id`)')])
                ->from('article_category_mapper')
                ->where([
                    'articles.id = article_category_mapper.article_id'
                ]);

        $keywordsSelect = clone $sql->select()
                ->columns(['keywordIds' => new Expression('GROUP_CONCAT(`keyword`)')])
                ->from('article_keyword_mapper')
                ->where([
                    'articles.id = article_keyword_mapper.article_id'
                ]);

        $sourceSelect = clone $sql->select()
                ->columns(['name'])
                ->from('sources')
                ->where([
                    'articles.source_id = sources.id'
                ])
                ;

        $featuredArticlesSelect = clone $sql->select()
                ->columns(['featured' => new Expression('IF (`id`, true, false)')])
                ->from('featured_articles')
                ->where([
                    'featured_articles.article_id = articles.id'
                ])
                ->limit(1)
                ;

        $select = $sql->select()
                ->columns([
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
                    'keywords' => $keywordsSelect,
                    'source' => $sourceSelect,
                    'featured' => $featuredArticlesSelect,
                    'source_id',
                    'status_id'
                ])
                ->from('articles')
                ->where([
//                    'status_id = 2',
//                    'articles.id' => 769736
                ])
                ->order('date DESC')
                ;

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        foreach ($results as $result) {
            $keywords = explode(',', $result['keywords']);
            $categories = explode(',', $result['categories']);

            $displayCategory = [];
            $topCategories = [];

            $result['categories'] = [];
            foreach ($categories as $category) {
                if (isset($fullCategories[$category])) {
                    $result['categories'][] = $fullCategories[$category]->code;
                    $displayCategory[] = [
                        'code' => $fullCategories[$category]->code,
                        'name' => $fullCategories[$category]->name
                    ];
                    if (isset($fullCategories[$fullCategories[$category]->parentId])) {
                        $topCategories[] = $fullCategories[$fullCategories[$category]->parentId]->code;
                    }
                }
            }
            $reorderedCategories = array_filter(array_unique(array_merge($topCategories, $result['categories'])));
            sort($reorderedCategories);

            $imageSelect = clone $sql->select()
                    ->columns(['url'])
                    ->from('article_images')
                    ->where([
                        'article_images.article_id = ?' => $result['id']
                    ])
                    ->order('id asc');

            $imageStatement = $sql->prepareStatementForSqlObject($imageSelect);
            $imagesResult = $imageStatement->execute();

            $images = $imagesResult->getResource()->fetchAll(\PDO::FETCH_COLUMN);

            $mediaSelect = clone $sql->select()
                    ->columns(['code'])
                    ->from('article_media')
                    ->where([
                        'article_media.article_id = ?' => $result['id']
                    ])
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
                    'keywords' => $keywords
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
