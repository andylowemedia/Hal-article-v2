<?php
$app->get('/health-check', App\Action\HealthCheckAction::class, 'health-check');
$app->get('/', App\Action\HealthCheckAction::class, 'default-health-check');

$app->get('/article', App\Action\ViewAction::class, 'view');
$app->post('/article', App\Action\AddAction::class, 'add');
// $app->patch('/article', App\Action\UpdateAction::class, 'partial-update');
// $app->put('/article', App\Action\UpdateAction::class, 'update');
// $app->delete('/article', App\Action\DeleteAction::class, 'delete');

$app->get('/search', App\Action\SearchAction::class, 'search');
$app->get('/summary', App\Action\SummaryAction::class, 'summary');
$app->get('/custom-feed', App\Action\CustomFeedAction::class, 'custom-feed');

$app->get('/category/code/{slug}', App\Action\CategoryAction::class, 'category')
    ->setOptions([
        'tokens' => ['slug' => '[0-9a-zA-Z-]+'],
    ]);
