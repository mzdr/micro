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

        $this->path = realpath($path) . DIRECTORY_SEPARATOR;

        return $this;
    }

    /**
     * Returns the current set path. Optionally you can provide a path that will be used instead if it exists,
     * otherwise it will be appended to the current one.
     *
     * @param string $path Path to prefer or append.
     * @return string
     */
    public function getPath(string $path): string
    {
        if (is_readable($path) === true) {
            return $path;
        }

        $compoundPath = $this->path . $path;
        $exists = realpath($compoundPath);

        if ($exists !== false) {
            return $exists;
        }

        throw new InvalidArgumentException(
            sprintf('Given path “%s” or root’ed path “%s” does not exist.', $path, $compoundPath)
        );
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
                $this->getPath($path)
            ),
            ...$args
        );
    }
}
