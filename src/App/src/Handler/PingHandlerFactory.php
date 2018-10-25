<?php
declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

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
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class)
        );
    }
}
