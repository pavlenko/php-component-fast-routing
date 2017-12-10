<?php

namespace PE\Component\FastRouting\Matcher;

use Psr\Http\Message\ServerRequestInterface;

class URLMatcher implements URLMatcherInterface
{
    /**
     * @inheritdoc
     */
    public function match(ServerRequestInterface $request)
    {
        // TODO: Implement match() method.
        return [];
    }
}