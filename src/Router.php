<?php

namespace PE\Component\FastRouting;

use PE\Component\CacheFile\CacheFile;
use PE\Component\FastRouting\Generator\URLGenerator;
use PE\Component\FastRouting\Generator\URLGeneratorInterface;
use PE\Component\FastRouting\Matcher\URLMatcher;
use PE\Component\FastRouting\Matcher\URLMatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    /**
     * @var URLGeneratorInterface
     */
    private $generator;

    /**
     * @var Cache
     */
    private $generatorCache;

    /**
     * @var URLMatcherInterface
     */
    private $matcher;

    /**
     * @var Cache
     */
    private $matcherCache;

    public function __construct(Cache $matcherCache = null, Cache $generatorCache = null)
    {
        $this->matcherCache   = $matcherCache;
        $this->generatorCache = $generatorCache;
    }

    /**
     * @inheritdoc
     */
    public function generate($name, array $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        return $this->getGenerator()->generate($name, $parameters, $referenceType);
    }

    /**
     * @return URLGeneratorInterface
     */
    public function getGenerator()
    {
        if (null !== $this->generator) {
            return $this->generator;
        }

        if (!$this->generatorCache) {
            return $this->generator = new URLGenerator();
        }

        $class = $this->generatorCache->cache(function(CacheFile $file, DumperInterface $dumper){
            $file->write($dumper->dump(), []);
        })->getClass();

        return $this->generator = new $class();
    }

    /**
     * @inheritdoc
     */
    public function match(ServerRequestInterface $request)
    {
        return $this->getMatcher()->match($request);
    }

    /**
     * @return URLMatcherInterface
     */
    public function getMatcher()
    {
        if (null !== $this->matcher) {
            return $this->matcher;
        }

        if (!$this->matcherCache) {
            return $this->matcher = new URLMatcher();
        }

        $class = $this->matcherCache->cache(function(CacheFile $file, DumperInterface $dumper){
            $file->write($dumper->dump(), []);
        })->getClass();

        return $this->matcher = new $class();
    }
}