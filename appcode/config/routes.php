<?php
$app->get('/', App\Action\HomePageAction::class, 'home');
$app->post('/', App\Action\AddAction::class, 'add');
