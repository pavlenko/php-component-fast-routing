<?php

namespace PE\Component\FastRouting;

use PE\Component\CacheFile\CacheFile;

class Cache
{
    /**
     * @var DumperInterface
     */
    private $dumper;

    /**
     * @var CacheFile
     */
    private $cache;

    public function __construct($path, DumperInterface $dumper, $debug = false)
    {
        $this->path   = $path;
        $this->cache  = new CacheFile($path, $debug);
        $this->dumper = $dumper;
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function cache(callable $callable)
    {
        if (!$this->cache->isFresh()) {
            $callable($this->cache, $this->dumper);
            require_once $this->path;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->dumper->getChildClass();
    }
}