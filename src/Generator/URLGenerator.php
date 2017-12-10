<?php

namespace PE\Component\FastRouting\Generator;

use PE\Component\FastRouting\CompiledRoute;
use PE\Component\FastRouting\Route;
use Psr\Http\Message\ServerRequestInterface;

class URLGenerator implements URLGeneratorInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var bool
     */
    protected $useTrailingSlash;

    /**
     * Constructor
     *
     * @param string $basePath
     * @param bool   $useTrailingSlash
     */
    public function __construct($basePath = null, $useTrailingSlash = false)
    {
        $this->basePath = $basePath ?: '';

        $this->useTrailingSlash = (bool) $useTrailingSlash;
    }

    /**
     * @inheritdoc
     */
    public function generate($name, array $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        // TODO: Implement generate() method.
        $route    = new Route();
        $compiled = new CompiledRoute();

        return $this->doGenerate($parameters, $referenceType, $route->getDefaults(), $route->getSchemes());
    }

    protected function doGenerate(array $parameters, $referenceType, array $defaults, array $routeSchemes = [])
    {
        if (null === $this->request) {
            throw new \RuntimeException('Unable to generate url without request object');
        }

        $route      = $this->map->getRoute($name);
        $parameters = array_merge($defaults, $parameters);

        $requestURI = $this->request->getUri();

        // Force scheme if mismatched
        $scheme = $requestURI->getScheme();
        if ($routeSchemes && !in_array($scheme, $routeSchemes, false)) {
            $scheme        = current($routeSchemes);
            $referenceType = self::ABSOLUTE_PATH;
        }

        // Force host if mismatched
        $host = $requestURI->getHost();
        if (($routeHost = $route->getHost()) && $host !== $routeHost) {
            $host = $routeHost;

            if (self::ABSOLUTE_PATH !== $referenceType) {
                $referenceType = self::NETWORK_PATH;
            }
        }

        // Force port if mismatched
        $port = $requestURI->getPort();
        if (($routePorts = $route->getPorts()) && !in_array($port, $routePorts, false)) {
            $port = current($routePorts);
        }

        $uri = '';

        // Apply scheme
        if (self::ABSOLUTE_PATH === $referenceType) {
            $uri .= $scheme . ':';
        }

        // Apply host and port
        if (self::ABSOLUTE_PATH === $referenceType || self::NETWORK_PATH === $referenceType) {
            $uri .= '//' . $host;

            if ($port && (('http' === $scheme && 80 !== $port) || ('https' === $scheme && 443 !== $port))) {
                $uri .= ':' . $port;
            }
        }

        $uri .= $this->basePath . $route->getPath();

        $tokens = [];

        // Apply required tokens
        preg_match_all('#{([a-z][a-zA-Z0-9_]*)}#', $uri, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            if (!array_key_exists($match[1], $parameters)) {
                throw new \InvalidArgumentException(sprintf(
                    'Parameter %s is required for route %s',
                    $match[1],
                    $route->getName()
                ));
            }

            $tokens[] = $match[1];

            // replace {id_key} with id_value
            $uri = str_replace('{' . $match[1] . '}', $parameters[$match[1]], $uri);
        }

        // Apply optional tokens
        preg_match('#{/([a-z][a-zA-Z0-9_,]*)}#', $uri, $matches);
        if ($matches) {
            $list  = explode(',', $matches[1]);
            $parts = [];

            //TODO if optional parameters more than 3 and not exists value for middle - fill nulls
            foreach ($list as $key) {
                if (array_key_exists($key, $parameters) && !in_array($key, $tokens, false)) {
                    $parts[] = $parameters[$key];
                    unset($parameters[$key]);
                }
            }

            // replace {/year_key,month_key} with /year_value/month_value
            $uri = str_replace('{/' . $matches[1] . '}', (count($parts) ? '/' : '') . implode('/', $parts), $uri);
        }

        $uri = rtrim($uri, '/');
        if ($this->useTrailingSlash) {
            $uri .= '/';
        }

        // Add a query string if needed
        $query = array_diff_key($parameters, array_flip($tokens));
        if ($query && $query = http_build_query($query, '', '&')) {
            // "/" and "?" can be left decoded for better user experience, see
            // http://tools.ietf.org/html/rfc3986#section-3.4
            $uri .= '?' . strtr($query, ['%2F' => '/']);
        }

        // Add a slash to prevent empty url
        return $uri ?: '/';
    }
}