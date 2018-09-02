<?php

use Console\Command\CreateIndexCommand;
use Console\Command\CreateArticleHistoryIndexCommand;
use Console\Command\DeleteIndexCommand;
use Console\Command\BuildArticlesIndexCommand;
use Console\Command\BuildArticleHistoryIndexCommand;

return [
    'commands' => [
        CreateIndexCommand::class,
        CreateArticleHistoryIndexCommand::class,
        DeleteIndexCommand::class,
        BuildArticlesIndexCommand::class,
        BuildArticleHistoryIndexCommand::class,
    ]
];
