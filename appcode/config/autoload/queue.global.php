<?php

declare(strict_types=1);

return [
    'queue' => [
        'events' => [
            'queue' => getenv('AWS_SQS_QUEUE'),
            'client' => [
                'endpoint'  => getenv('AWS_SQS_QUEUE'),
                'profile'   => 'default',
                'region'    => 'eu-west-1',
                'version'   => '2012-11-05'
            ],
        ]
    ]
];