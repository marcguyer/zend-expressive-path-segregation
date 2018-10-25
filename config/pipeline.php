<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\MiddlewareFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

/*
 * Setup global app middleware pipeline
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->pipe(ErrorHandler::class);
    $app->pipe(ServerUrlMiddleware::class);

    $app->pipe('/api', 'App\Pipeline');

    $app->pipe(NotFoundHandler::class);
};
