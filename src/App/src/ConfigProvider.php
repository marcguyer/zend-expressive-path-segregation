<?php

declare(strict_types=1);

namespace App;

use Zend\Expressive\Hal\LinkGeneratorFactory;
use Zend\Expressive\Hal\LinkGenerator\ExpressiveUrlGeneratorFactory;
use Zend\Expressive\Hal\Metadata\MetadataMap;
use Zend\Expressive\Hal\ResourceGeneratorFactory;
use Zend\Expressive\Helper\UrlHelperFactory;
use Zend\Expressive\Helper\UrlHelperMiddlewareFactory;
use Zend\Expressive\Router\Middleware\RouteMiddlewareFactory;
use Zend\Expressive\Hal\Metadata\RouteBasedResourceMetadata;
use Zend\Hydrator\ObjectProperty as ObjectPropertyHydrator;
use stdClass;

/**
 * The configuration provider for the App module.
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array.
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            MetadataMap::class => $this->getHalConfig(),
        ];
    }

    /**
     * Returns the container dependencies.
     *
     * @return array
     */
    public function getDependencies(): array
    {
        $ns = __NAMESPACE__;

        return [
            'invokables' => [
            ],
            'factories' => [
                // module-specific class name => factory
                $ns . '\LinkGenerator' => new LinkGeneratorFactory($ns . '\UrlGenerator'),
                $ns . '\ResourceGenerator' => new ResourceGeneratorFactory($ns . '\LinkGenerator'),
                $ns . '\RouteMiddleware' => new RouteMiddlewareFactory($ns . '\Router'),
                $ns . '\Router' => RouterFactory::class,
                $ns . '\RouteCollector' => RouteCollectorFactory::class,
                $ns . '\UrlHelper' => new UrlHelperFactory('/api', $ns . '\Router'),
                $ns . '\UrlHelperMiddleware' => new UrlHelperMiddlewareFactory($ns . '\UrlHelper'),
                $ns . '\UrlGenerator' => new ExpressiveUrlGeneratorFactory($ns . '\UrlHelper'),

                // Our handler:
                Handler\PingHandler::class => Handler\PingHandlerFactory::class,

                // And our pipeline:
                $ns . '\Pipeline' => PipelineFactory::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getHalConfig(): array
    {
        return [
            [
                '__class__' => RouteBasedResourceMetadata::class,
                'resource_class' => stdClass::class,
                'route' => 'api.ping',
                'extractor' => ObjectPropertyHydrator::class,
            ],
        ];
    }
}
