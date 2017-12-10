<?php

namespace PE\Component\FastRouting;

use PE\Component\FastRouting\Generator\URLGeneratorInterface;
use PE\Component\FastRouting\Matcher\URLMatcherInterface;

interface RouterInterface extends URLGeneratorInterface, URLMatcherInterface
{

}