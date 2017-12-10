<?php

namespace PE\Component\FastRouting;

interface DumperInterface
{
    /**
     * @return string
     */
    public function dump();

    /**
     * @return string
     */
    public function getBaseClass();

    /**
     * @return string
     */
    public function getChildClass();
}