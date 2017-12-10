<?php

namespace PE\Component\FastRouting\Generator;

interface URLGeneratorInterface
{
    /**
     * Generates an absolute URL, e.g. "http://example.com/dir/file".
     */
    const ABSOLUTE_URL = 0;

    /**
     * Generates an absolute path, e.g. "/dir/file".
     */
    const ABSOLUTE_PATH = 1;

    /**
     * Generates a relative path based on the current request path, e.g. "../parent-file".
     *
     * @see UrlGenerator::getRelativePath()
     */
    const RELATIVE_PATH = 2;

    /**
     * Generates a network path, e.g. "//example.com/dir/file".
     * Such reference reuses the current scheme but specifies the host.
     */
    const NETWORK_PATH = 3;

    /**
     * @param string $name
     * @param array  $parameters
     * @param int    $referenceType
     *
     * @return string
     */
    public function generate($name, array $parameters = [], $referenceType = self::ABSOLUTE_PATH);
}