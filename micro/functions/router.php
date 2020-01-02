<?php

declare(strict_types=1);

namespace µ;

use FastRoute\DataGenerator\GroupCountBased as DataGenerator;
use FastRoute\RouteParser\Std as RouteParser;

/**
 * Provides access to a simple wrapper for FastRoute.
 *
 * @return Router
 * @see https://github.com/nikic/FastRoute
 */
function router(): Router
{
    static $router;

    if ($router instanceof Router === true) {
        return $router;
    }

    return $router = new Router(
        new RouteParser,
        new DataGenerator
    );
}
