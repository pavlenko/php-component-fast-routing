<?php

namespace PETest\Component\FastRouting;

use PE\Component\FastRouting\CompiledRoute;
use PE\Component\FastRouting\RouteCompiler;
use PE\Component\FastRouting\Route;

class RouteCompilerTest extends \PHPUnit_Framework_TestCase
{
    public function testCompiledInstance()
    {
        $compiled = (new RouteCompiler())->compile(new Route());
        static::assertInstanceOf(CompiledRoute::class, $compiled);
    }

    public function testCompiledPath()
    {
        $route = new Route();
        $route->setPath('/blog/{category}/{post}{/y,m,d}');

        $compiled = (new RouteCompiler())->compile($route);

        static::assertSame(
            '/blog/(?P<category>[^\/]+)/(?P<post>[^\/]+)(/(?P<y>[^\/]+)(/(?P<m>[^\/]+)(/(?P<d>[^\/]+))?)?)?',
            $compiled->getPathRegex()
        );

        static::assertSame([
            'y' => false,
            'm' => false,
            'd' => false,
            'category' => true,
            'post' => true,
        ], $compiled->getRequired());
    }

    public function testCompiledHost()
    {
        $route = new Route();
        $route->setHost('{locale,lang.}{user}.example.com');

        $compiled = (new RouteCompiler())->compile($route);

        static::assertSame(
            '(((?P<locale>[^\.]+)\.)?(?P<lang>[^\.]+)\.)?(?P<user>[^\.]+).example.com',
            $compiled->getHostRegex()
        );

        static::assertSame([
            'locale' => false,
            'lang'   => false,
            'user'   => true,
        ], $compiled->getRequired());
    }
}
