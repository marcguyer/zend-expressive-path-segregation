<?php

declare(strict_types=1);

namespace Admin\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;
use Admin\AdminStdClass;

/**
 * Ping Request Handler
 */
class PingHandler implements RequestHandlerInterface
{
    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;
    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    /**
     * @param ResourceGenerator  $resourceGenerator
     * @param HalResponseFactory $responseFactory
     */
    public function __construct(
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    ) {
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $obj = new AdminStdClass();
        $obj->ack = time();
        return $this->responseFactory->createResponse(
            $request,
            $this->resourceGenerator->fromObject($obj, $request)
        );
    }
}
