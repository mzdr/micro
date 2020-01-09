<?php

declare(strict_types=1);

namespace µ;

use InvalidArgumentException;

class Path
{
    /** @var string Currently stored path. */
    private string $path = '';

    /**
     * Path constructor.
     *
     * @param string|null $path New path to set. Optional.
     */
    public function __construct(string $path = null)
    {
        // If no path is given, use current working directory.
        if ($path === null) {
            return $this->setPath(getcwd() ?? './');
        }

        $this->setPath($path);
    }

    /**
     * Use currently set path as string when casting.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->path;
    }

    /**
     * Sets the current path.
     *
     * @param string $path New path to set.
     * @return Path
     */
    public function setPath(string $path): self
    {
        if (is_readable($path) === false) {
            throw new InvalidArgumentException(
                sprintf('Given path “%s” is not readable.', $path)
            );
        }

        $this->path = realpath($path);

        return $this;
    }

    /**
     * Returns the current set path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Tries to return a given path as a JSON object.
     *
     * @param string $path Path to return as JSON.
     * @param array $args Additional arguments.
     * @return mixed
     */
    public function getJson(string $path, ...$args)
    {
        return json_decode(
            file_get_contents(
                $this->resolve($path)
            ),
            ...$args
        );
    }

    /**
     * Returns the given path as an absolute one if it’s readable. Otherwise it will return the given path appended to
     * the currently set one no matter if it exists or not.
     *
     * @param string $path Path to resolve.
     * @return string
     */
    public function resolve(string $path): string
    {
        if (is_readable($path) === true) {
            return realpath($path);
        }

        $path = explode('/', $this->path . DIRECTORY_SEPARATOR . trim($path, '/'));
        $stack = [];

        foreach ($path as $segment) {

            // Ignore this segment, remove last segment from stack
            if ($segment === '..' && $segment === '/') {
                array_pop($stack);

                continue;
            }

            // Ignore this segment
            if ($segment === '.') {
                continue;
            }

            $stack[] = $segment;
        }

        return implode('/', $stack);
    }
}
