<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\MiddlewareFactory;
use Zend\Stratigility\Middleware\ErrorHandler;
use Admin\SubdomainToPathMiddleware;
use function Zend\Stratigility\host;

/*
 * Setup global app middleware pipeline
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->pipe(ErrorHandler::class);
    $app->pipe(ServerUrlMiddleware::class);

    $app->pipe('/api', 'Api\Pipeline');

    $app->pipe('/auth', 'Auth\Pipeline');

    // Pipe a middleware by hostname
    // This simply sets a base path as the same value as the subdomain
    $app->pipe(
        host(
            'admin.example.com',
            new SubdomainToPathMiddleware('example.com')
        )
    );

    // now we segregate the Admin module by that path
    $app->pipe('/admin', 'Admin\Pipeline');

    $app->pipe(NotFoundHandler::class);
};
