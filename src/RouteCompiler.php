<?php

namespace PE\Component\FastRouting;

//TODO add preg_quote delimiters
class RouteCompiler
{
    const REGEX_PATH_REQUIRED = '#{([a-z][a-zA-Z0-9_]*)}#';
    const REGEX_PATH_OPTIONAL = '#{/([a-z][a-zA-Z0-9_,]*)}#';
    const REGEX_HOST_REQUIRED = '#{([a-z][a-zA-Z0-9_]*)}#';
    const REGEX_HOST_OPTIONAL = '#{([a-z][a-zA-Z0-9_,]*)\.}#';

    /**
     * @param Route $route
     *
     * @return CompiledRoute
     */
    public function compile(Route $route)
    {
        $hostRegex = null;
        $pathRegex = null;

        $attributes = [];

        if ($host = $route->getHost()) {
            $this->setRegexOptionalParts($host, static::REGEX_HOST_OPTIONAL, '', '.', false, $attributes);
            $this->setRegexRequiredParts($host, static::REGEX_HOST_REQUIRED, $route->getRequirements(), '[^\.]+', $attributes);

            $hostRegex = $host;
        }

        list($prefix, $path) = array_pad(explode('{', $route->getPath(), 2), 2, null);

        if ($path) {
            $path = '{' . $path;
            $this->setRegexOptionalParts($path, static::REGEX_PATH_OPTIONAL, '/', '', true, $attributes);
            $this->setRegexRequiredParts($path, static::REGEX_PATH_REQUIRED, $route->getRequirements(), '[^\/]+', $attributes);
            $pathRegex = preg_quote($prefix) . $path;
        }

        return new CompiledRoute($route->getName(), $prefix, $route->getDefaults(), $attributes, $pathRegex, $hostRegex);
    }

    /**
     * @param string $regex        Regex string to process
     * @param string $pattern      Pattern to extract placeholders
     * @param array  $tokens       Route tokens to validate pattern
     * @param string $defaultToken Default token if other one is not configured in route
     * @param array  $attributes   Required attributes map
     */
    private function setRegexRequiredParts(&$regex, $pattern, array $tokens, $defaultToken, array &$attributes = [])
    {
        preg_match_all($pattern, $regex, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $name       = $match[1];
            $subPattern = array_key_exists($name, $tokens)
                ? '(?P<' . $name . '>' . $tokens[$name] . ')'
                : '(?P<' . $name . '>' . $defaultToken . ')';

            if (!array_key_exists($name, $attributes)) {
                $attributes[$name] = true;
            }

            $regex = str_replace("{{$name}}", $subPattern, $regex);
        }
    }

    /**
     * @param string $regex       Regex string to process
     * @param string $pattern     Pattern to extract placeholders
     * @param string $prefix      Part regex prefix
     * @param string $suffix      Part regex suffix
     * @param bool   $directOrder Parts detect order
     * @param array  $attributes  Required attributes map
     */
    private function setRegexOptionalParts(&$regex, $pattern, $prefix, $suffix, $directOrder = true, array &$attributes = [])
    {
        preg_match($pattern, $regex, $match);
        if ($match) {
            $list = explode(',', $match[1]);
            $head = '';
            $tail = '';

            //TODO
            // If the optional set is the first part of the path,
            // make sure there is a leading slash in the replacement before the optional attribute.
            /*if (0 === strpos($host, '{/')) {
                $name = array_shift($list);
                $head = "/({{$name}})?";
            }*/

            foreach ($list as $name) {
                $head .= '(' . ($prefix ? preg_quote($prefix) : '') . ($directOrder ? '{' . $name . '}' : '');
                $tail .= (!$directOrder ? '{' . $name . '}' : '') . ($suffix ? preg_quote($suffix) : '') . ')?';

                if (!array_key_exists($name, $attributes)) {
                    $attributes[$name] = false;
                }
            }

            $regex = str_replace($match[0], $head . $tail, $regex);
        }
    }
}