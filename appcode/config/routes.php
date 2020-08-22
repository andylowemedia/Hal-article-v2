<?php

declare(strict_types=1);

use App\Handler\EditHandler;
use Psr\Container\ContainerInterface;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
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
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->route('/health-check', App\Handler\HealthCheckHandler::class, ['HEAD'],'health-check');

//    $app->get('/summary', App\Handler\SummaryHandler::class, 'summary');
    $app->get('/custom-feed', App\Handler\CustomFeedHandler::class, 'custom-feed');
    $app->get('/search', App\Handler\SearchHandler::class, 'search');
    $app->get('/{slug}', App\Handler\ViewHandler::class, 'view')
        ->setOptions([
            'tokens' => [
                'slug' => '[0-9a-zA-Z-]+'
            ]
        ]);
    $app->post('/', [App\Middleware\ArticleAddValidationMiddleware::class, App\Handler\AddHandler::class], 'add');
    $app->put('/{id}', App\Handler\EditHandler::class, 'edit');
    $app->delete('/{id}', App\Handler\DeleteHandler::class, 'delete');


//    $app->get('/related', App\Handler\RelatedHandler::class, 'related');
    //$app->post('/article/history/add', App\Handler\HistoryAddHandler::class, 'history-add');

    //$app->patch('/article', App\Handler\UpdateSocialPostsHandler::class, 'update-social-posts');

    //$app->get('/category/code/{slug}', App\Handler\CategoryHandler::class, 'category')
    //    ->setOptions([
    //        'tokens' => ['slug' => '[0-9a-zA-Z-]+'],
    //    ]);
};
