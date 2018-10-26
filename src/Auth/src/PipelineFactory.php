<?php

declare(strict_types=1);

namespace Auth;

use Psr\Container\ContainerInterface;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Router\RouteCollector;
use Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware;
use Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;
use Zend\ProblemDetails\ProblemDetailsMiddleware;
use Zend\ProblemDetails\ProblemDetailsNotFoundHandler;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Pipeline for App module.
 */
class PipelineFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return MiddlewarePipe
     */
    public function __invoke(ContainerInterface $container): MiddlewarePipe
    {
        $f = $container->get(MiddlewareFactory::class);

        $p = new MiddlewarePipe();
        $p->pipe($f->lazy(ProblemDetailsMiddleware::class));
        $p->pipe($f->lazy(__NAMESPACE__ . '\RouteMiddleware')); // module-specific!
        $p->pipe($f->lazy(ImplicitHeadMiddleware::class));
        $p->pipe($f->lazy(ImplicitOptionsMiddleware::class));
        $p->pipe($f->lazy(MethodNotAllowedMiddleware::class));
        $p->pipe($f->lazy(__NAMESPACE__ . '\UrlHelperMiddleware')); // module-specific!
        $p->pipe($f->lazy(DispatchMiddleware::class));
        $p->pipe($f->lazy(ProblemDetailsNotFoundHandler::class));

        $router = $container->get(__NAMESPACE__ . '\Router');
        $r = new RouteCollector($router);

        $r->get('/ping', $f->lazy(Handler\PingHandler::class), 'auth.ping');

        return $p;
    }

}
