<?php
declare(strict_types=1);

namespace Api\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Hal\HalResponseFactory;

/**
 * Factory for Ping Request Handler
 */
class PingHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PingHandler
     */
    public function __invoke(ContainerInterface $container): PingHandler
    {
        return new PingHandler(
            $container->get('Api\ResourceGenerator'),
            $container->get(HalResponseFactory::class)
        );
    }
}
