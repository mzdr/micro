<?php

namespace µ;

use \Bramus\Router\Router;

function router(): Router {
    static $router;

    if ($router instanceof Router === false) {
        $router = new Router;
    }

    return $router;
};
