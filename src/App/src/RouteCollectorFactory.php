<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;
use Zend\Expressive\MiddlewareFactory;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;
use FastRoute\DataGenerator;

/**
 * RouteCollector for App module.
 */
class RouteCollectorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return RouteCollector
     */
    public function __invoke(ContainerInterface $container): RouteCollector
    {
        $r = new RouteCollector(
            new RouteParser\Std(),
            new DataGenerator\GroupCountBased()
        );

        $f = $container->get(MiddlewareFactory::class);

        $r->get('/ping', $f->lazy(Handler\PingHandler::class), 'api.ping');

        return $r;
    }
}
