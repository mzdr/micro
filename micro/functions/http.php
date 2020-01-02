<?php

declare(strict_types=1);

namespace µ;

/**
 * Simple HTTP helper functions.
 *
 * @return HTTP
 */
function http(): HTTP
{
    static $http;

    if ($http instanceof HTTP === true) {
        return $http;
    }

    return $http = new HTTP();
}
