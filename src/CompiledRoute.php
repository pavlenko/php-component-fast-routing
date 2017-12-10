<?php

namespace PE\Component\FastRouting;

class CompiledRoute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $pathRegex;

    /**
     * @var string
     */
    private $hostRegex;

    /**
     * @var array
     */
    private $defaults;

    /**
     * @var array
     */
    private $required;

    /**
     * @param string $name
     * @param string $prefix
     * @param array  $defaults
     * @param array  $required
     * @param string $pathRegex
     * @param string $hostRegex
     */
    public function __construct($name, $prefix, array $defaults, array $required, $pathRegex = null, $hostRegex = null)
    {
        $this->name      = $name;
        $this->prefix    = $prefix;
        $this->defaults  = $defaults;
        $this->required  = $required;
        $this->pathRegex = $pathRegex;
        $this->hostRegex = $hostRegex;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getPathRegex()
    {
        return $this->pathRegex;
    }

    /**
     * @return string
     */
    public function getHostRegex()
    {
        return $this->hostRegex;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @return array
     */
    public function getRequired()
    {
        return $this->required;
    }
}