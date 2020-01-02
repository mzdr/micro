<?php

declare(strict_types=1);

namespace µ;

/**
 * Define root path for all path related strings.
 *
 * @param string|null $path Define new root path.
 * @return Path
 */
function root(string $path = null): Path
{
    /** @var Path $root Path instance. */
    static $root;

    if ($root instanceof Path === true) {
        return $path ? $root->setPath($path) : $root;
    }

    return ($root = new Path($path));
}
