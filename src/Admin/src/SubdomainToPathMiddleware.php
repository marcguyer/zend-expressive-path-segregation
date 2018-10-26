<?php

declare(strict_types=1);

namespace Admin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware which converts a subdomain to a base path
 */
class SubdomainToPathMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $baseDomain;

    /**
     * @param string    $baseDomain
     */
    public function __construct(string $baseDomain)
    {
        $this->baseDomain = $baseDomain;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();

        $host = $uri->getHost();

        // no subdomain or host is something less than baseDomain
        if (strlen($host) <= strlen($this->baseDomain)) {
            return $handler->handle($request);
        }

        // if request host does not end in baseDomain, dump
        if (0 !== strpos(strrev($host), strrev($this->baseDomain))) {
            return $handler->handle($request);
        }

        $subdomain = substr(
            $host,
            0,
            strlen($host) - strlen($this->baseDomain)
        );

        // if $subdomain does not end in a dot, something's wrong
        if ('.' !== substr($subdomain, -1)) {
            throw \Exception(
                "Subdomain '$subdomain' does not end with a dot"
            );
        }

        $path = $uri->getPath();

        $newBasePath = '/' . str_replace('.', '/', $subdomain);

        // request already includes the base path
        if (0 === strpos($path, $newBasePath)) {
            return $handler->handle($request);
        }

        $newPath = $newBasePath . $path;

        $request = $request->withUri($uri->withPath($newPath));

        return $handler->handle($request);
    }
}
