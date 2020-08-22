<?php
namespace Console\Command;

use Laminas\Db\Adapter\AdapterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;


use Elasticsearch\Client as ElasticsearchClient;

class BuildArticlesIndexCommand extends Command
{
    /**
     * @var ElasticsearchClient
     */
    private ElasticsearchClient $elasticsearchClient;

    /**
     * @var AdapterInterface
     */
    private AdapterInterface $articlesDbAdapter;

    /**
     * @var array
     */
    private array $apiConfig;

    /**
     * @var Sql
     */
    private Sql $sql;

    /**
     * @var array
     */
    private array $fullCategories;

    public function __construct(ElasticsearchClient $elasticsearchClient, AdapterInterface $articlesDbAdapter, array $apiConfig)
    {
        $this->elasticsearchClient = $elasticsearchClient;
        $this->articlesDbAdapter = $articlesDbAdapter;
        $this->apiConfig = $apiConfig;
        $this->sql = new Sql($this->articlesDbAdapter);
        parent::__construct(null);
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

        $this->fullCategories = $this->loadCategories();

        $count = $this->countOfArticles();

        for ($n = 0; $n < $count; $n += 1000) {
            $select = $this->sql->select()
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
                    'categories' => $this->buildCategoryQuery(),
                    'keywords' => $this->buildKeywordQuery(),
                    'source' => $this->buildSourceQuery(),
                    'featured' => $this->buildFeaturedArticleQuery(),
                    'source_id',
                    'status_id'
                ])
                ->from('articles')
                ->where([
                    'status_id = 2'
                ])
                ->order('date DESC')
                ->limit(1000)
                ->offset($n);

            $statement = $this->sql->prepareStatementForSqlObject($select);
            $results = $statement->execute();

            foreach ($results as $result) {

                $params = $this->parseRowToElasticsearch($result);

                $response = $this->elasticsearchClient->index($params);

                print_r($response);
            }
        }
        $end = microtime(true);

        $time = $end - $start;

        $output->writeln([
            "Built articles index in {$time} seconds",
            '========================',
        ]);
    }

    protected function loadCategories()
    {
        $environment = getenv('APP_ENV');

        if ($environment === 'production') {
            $guzzleClient = new \GuzzleHttp\Client();

            $res = $guzzleClient->request('GET', $this->apiConfig['category'] . '/list?all-categories=true');

            $jsonString = $res->getBody()->getContents();
        } else {
            $jsonString = <<<JSON
{"categories":[{"id":"1","code":"currentAffairs","name":"Current Affairs","parentId":null},
{"id":"2","code":"politics","name":"Politics","parentId":null},{"id":"3","code":"business","name":"Business","parentId":null},
{"id":"4","code":"science","name":"Science","parentId":null},{"id":"5","code":"technology","name":"Technology","parentId":null},
{"id":"6","code":"entertainment","name":"Entertainment \u0026 Arts","parentId":null},{"id":"7","code":"education","name":"Education","parentId":null},
{"id":"8","code":"lifestyle","name":"Lifestyle","parentId":null},{"id":"9","code":"science-environment","name":"Environment","parentId":"4"},
{"id":"10","code":"science-health","name":"Health","parentId":"4"},{"id":"11","code":"entertainment-films","name":"Films/Movies","parentId":"6"},
{"id":"12","code":"entertainment-tv","name":"TV \u0026 Radio","parentId":"6"},{"id":"13","code":"entertainment-music","name":"Music","parentId":"6"},
{"id":"14","code":"entertainment-videoGames","name":"Computer \u0026 Video Games","parentId":"6"},
{"id":"15","code":"entertainment-theatre","name":"Theatre","parentId":"6"},{"id":"16","code":"lifestyle-fashion","name":"Fashion","parentId":"8"},
{"id":"17","code":"entertainment-comicBooks","name":"Comic Books/Graphic Novels","parentId":"6"},
{"id":"18","code":"currentAffairs-world","name":"World","parentId":"1"},{"id":"19","code":"currentAffairs-UK","name":"UK","parentId":"1"},
{"id":"20","code":"entertainment-books","name":"Books","parentId":"6"},{"id":"21","code":"columnists","name":"Columnists","parentId":null},
{"id":"22","code":"travel","name":"Travel","parentId":null},{"id":"23","code":"sports","name":"Sports","parentId":null},
{"id":"24","code":"finance","name":"Finance","parentId":null},{"id":"25","code":"entertainment-culture","name":"Culture","parentId":"6"},
{"id":"26","code":"science-general","name":"General","parentId":"4"},{"id":"27","code":"entertainment-art","name":"Art","parentId":"6"},
{"id":"28","code":"media","name":"Media","parentId":null},{"id":"29","code":"weather","name":"Weather","parentId":null},
{"id":"30","code":"entertainment-people","name":"People","parentId":"6"},{"id":"31","code":"lifestyle-foodDrink","name":"Food \u0026 Drink","parentId":"8"},
{"id":"32","code":"lifestyle-mens","name":"Mens","parentId":"8"},{"id":"33","code":"lifestyle-womens","name":"Womens","parentId":"8"},
{"id":"34","code":"entertainment-general","name":"General","parentId":"6"}]}
JSON;
        }

        $categoryData = \json_decode($jsonString);

        $fullCategories = [];
        foreach ($categoryData->categories as $categoryRow) {
            $fullCategories[$categoryRow->id] = $categoryRow;
        }
        return $fullCategories;
    }

    protected function countOfArticles()
    {
        $select = $this->sql->select()
            ->columns([
                'count' => new Expression('count(id)')
            ])
            ->from('articles')
            ->where([
                'status_id = 2',
            ]);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        return $results->current()['count'];
    }

    protected function buildCategoryQuery()
    {
        return clone $this->sql->select()
            ->columns(['categoryIds' => new Expression('GROUP_CONCAT(`category_id`)')])
            ->from('article_category_mapper')
            ->where([
                'articles.id = article_category_mapper.article_id'
            ]);
    }

    protected function buildKeywordQuery()
    {
        return clone $this->sql->select()
            ->columns(['keywordIds' => new Expression('GROUP_CONCAT(`keyword`)')])
            ->from('article_keyword_mapper')
            ->where([
                'articles.id = article_keyword_mapper.article_id'
            ]);
    }

    protected function buildSourceQuery()
    {
        return clone $this->sql->select()
            ->columns(['name'])
            ->from('sources')
            ->where([
                'articles.source_id = sources.id'
            ]);
    }

    protected function buildFeaturedArticleQuery()
    {
        return $this->sql->select()
            ->columns(['featured' => new Expression('IF (id, true, false)')])
            ->from('featured_articles')
            ->where([
                'featured_articles.article_id = articles.id'
            ]);
    }

    protected function parseRowToElasticsearch(array $result)
    {
        $keywords = explode(',', $result['keywords']);
        $categories = explode(',', $result['categories']);

        $displayCategory = [];
        $topCategories = [];

        $result['categories'] = [];
        foreach ($categories as $category) {
            if (isset($this->fullCategories[$category])) {
                $result['categories'][] = $this->fullCategories[$category]->code;
                $displayCategory[] = [
                    'code' => $this->fullCategories[$category]->code,
                    'name' => $this->fullCategories[$category]->name
                ];
                if (isset($this->fullCategories[$this->fullCategories[$category]->parentId])) {
                    $topCategories[] = $this->fullCategories[$this->fullCategories[$category]->parentId]->code;
                }
            }
        }
        $reorderedCategories = array_filter(array_unique(array_merge($topCategories, $result['categories'])));
        sort($reorderedCategories);

        $imageSelect = clone $this->sql->select()
            ->columns(['url'])
            ->from('article_images')
            ->where([
                'article_images.article_id = ?' => $result['id']
            ])
            ->order('id asc');

        $imageStatement = $this->sql->prepareStatementForSqlObject($imageSelect);
        $imagesResult = $imageStatement->execute();

        $images = $imagesResult->getResource()->fetchAll(\PDO::FETCH_COLUMN);

        $mediaSelect = clone $this->sql->select()
            ->columns(['code'])
            ->from('article_media')
            ->where([
                'article_media.article_id = ?' => $result['id']
            ])
            ->order('id asc');

        $mediaStatement = $this->sql->prepareStatementForSqlObject($mediaSelect);
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

        return $params;
    }
}
