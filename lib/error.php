<?php

namespace µ;

use League\BooBoo\BooBoo;
use League\BooBoo\Formatter\HtmlTableFormatter;

/**
 * Custom error handler for PHP that allows for the execution of handlers
 * and formatters for viewing and managing errors in development and production.
 *
 * @return BooBoo
 * @see https://github.com/thephpleague/booboo/
 */
function error(): BooBoo
{
    static $eh;

    if ($eh instanceof BooBoo === true) {
        return $eh;
    }

    $eh = new BooBoo([new HtmlTableFormatter]);
    $eh->register();

    return $eh;
}
