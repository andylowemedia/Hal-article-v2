<?php

declare(strict_types=1);

use Laminas\Db\Adapter\AdapterAbstractServiceFactory;

return [
    'dependencies' => [
        'factories' => [
            'ArticlesDbAdapter' => AdapterAbstractServiceFactory::class,
        ]
    ],

    'db' => [
        'adapters' => [
            'ArticlesDbAdapter' => [
                'driver'         => 'Pdo_Mysql',
                'options' => [
                    'buffer_results' => true,
                ],
                'driver_options' => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ],
                'hostname' => getenv('DB_ARTICLE_HOST'),
                'database' => getenv('DB_ARTICLE_SCHEMA'),
                'username' => getenv('DB_ARTICLE_USER'),
                'password' => getenv('DB_ARTICLE_PASSWORD'),
            ],
        ],
    ],
    'elasticsearch' => [
        'host' => getenv('ELASTICSEARCH_ARTICLE_HOST'),
    ]
];
