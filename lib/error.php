<?php

namespace µ;

use League\BooBoo\BooBoo;
use League\BooBoo\Formatter\HtmlFormatter;
use mzdr\OhSnap\Formatter\PrettyFormatter;

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

    $fatal = new PrettyFormatter([
        'footer' => sprintf('µ v%s', VERSION)
    ]);

    $trivial = new HtmlFormatter;

    // Use OhSnap formatter only for fatal errors…
    $fatal->setErrorLimit(E_ERROR | E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_PARSE);

    // Everything else should be printed just like PHP does it…
    $trivial->setErrorLimit(E_ALL);

    $eh = new BooBoo([$trivial, $fatal]);
    $eh->register();

    return $eh;
}
