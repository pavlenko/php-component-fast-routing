<?php

namespace PE\Component\FastRouting\Matcher;

use Psr\Http\Message\ServerRequestInterface;

interface URLMatcherInterface
{
    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher can not find information, it must throw exception.
     *
     * @param ServerRequestInterface $request The incoming request
     *
     * @return array An array of parameters
     */
    public function match(ServerRequestInterface $request);
}