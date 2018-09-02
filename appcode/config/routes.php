<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
$app->get('/health-check', App\Handler\HealthCheckHandler::class, 'health-check');
$app->get('/', App\Handler\HealthCheckHandler::class, 'default-health-check');

$app->get('/article', App\Handler\ViewHandler::class, 'view');
$app->post('/article', App\Handler\AddHandler::class, 'add');
$app->put('/article', App\Handler\EditHandler::class, 'edit');
$app->delete('/article', App\Handler\DeleteHandler::class, 'delete');

$app->get('/related', App\Handler\RelatedHandler::class, 'related');

$app->post('/article/history/add', App\Handler\HistoryAddHandler::class, 'history-add');



$app->get('/search', App\Handler\SearchHandler::class, 'search');
$app->get('/summary', App\Handler\SummaryHandler::class, 'summary');
$app->get('/custom-feed', App\Handler\CustomFeedHandler::class, 'custom-feed');

$app->get('/category/code/{slug}', App\Handler\CategoryHandler::class, 'category')
    ->setOptions([
        'tokens' => ['slug' => '[0-9a-zA-Z-]+'],
    ]);
};
