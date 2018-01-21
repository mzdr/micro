<?php

namespace µ;

use Bramus\Router\Router;

/**
 * Provides access to the Bramus Router.
 *
 * @return Router
 * @see https://github.com/bramus/router
 */
function router(): Router {
    static $router;

    if ($router instanceof Router === false) {
        $router = new Router;
    }

    return $router;
};
