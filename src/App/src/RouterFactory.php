<?php

declare(strict_types=1);

namespace App;

use Zend\Expressive\Router\FastRouteRouter;
use Psr\Container\ContainerInterface;

/**
 * Router for App module.
 */
class RouterFactory
{
    /**
     * @param ContainerInterface $container
     */
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config')
            ? $container->get('config')
            : [];

        $config = $config['router']['fastroute'] ?? [];

        $collector = $container->get(__NAMESPACE__ . '\RouteCollector');

        return new FastRouteRouter($collector, null, $config);
    }
}
