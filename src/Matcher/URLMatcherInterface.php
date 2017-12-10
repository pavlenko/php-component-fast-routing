<?php

namespace PE\Component\FastRouting\Matcher;

use Psr\Http\Message\ServerRequestInterface;

interface URLMatcherInterface
{
    /**
     * @inheritdoc
     */
    public function match(ServerRequestInterface $request);
}