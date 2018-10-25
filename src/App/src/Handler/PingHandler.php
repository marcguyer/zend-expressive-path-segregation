<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;

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
        return $this->responseFactory->createResponse(
            $request,
            $this->resourceGenerator->fromObject(
                (object) ['ack' => time()],
                $request
            )
        );
    }
}
