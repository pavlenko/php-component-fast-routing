<?php

namespace PE\Component\FastRouting;

class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var array
     */
    protected $schemes = [];

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * @var array
     */
    protected $requirements = [];

    /**
     * @var array
     */
    protected $extras = [];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Route
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return array
     */
    public function getSchemes()
    {
        return $this->schemes;
    }

    /**
     * @param array $schemes
     * @return Route
     */
    public function setSchemes(array $schemes)
    {
        $this->schemes = $schemes;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     * @return Route
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param array $defaults
     * @return Route
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param array $requirements
     * @return Route
     */
    public function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * @param array $extras
     * @return Route
     */
    public function setExtras(array $extras)
    {
        $this->extras = $extras;
        return $this;
    }
}